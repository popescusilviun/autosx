<?php

namespace AppBundle\Command;

use AppBundle\Entity\Model;
use AppBundle\Entity\Motorizare;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Toolkit\SimpleHtmlDom;
use Toolkit\Tools\UtilsTool;

/**
 * @author Silviu Popescu <popescu.silviun@gmail.com>
 */
class FindMotorizariCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('find:motorizari')
            ->setDescription('Find Motorizari on website')
            ->addOption('slug', null, InputOption::VALUE_NONE, 'Make slugs?')
            ->setHelp(<<<EOT
The <info>%command.name%</info>Find models
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        if($input->getOption('slug')) {
            $this->makeSlugs($em, $output);

        } else {
            echo "error"; exit;
            $current_date = date('Y-m-d', strtotime(date("Y-m-d") . '-5 days'));


            $qmmarca = $em->createQueryBuilder();
            $qmarca = $qmmarca->select(array('m'))
                ->from('AppBundle:Marca', 'm')
                ->where("m.createdAt < '$current_date'")
                ->andWhere('m.remoteId IS NOT NULL')
                ->andWhere('m.isActive = 1')
                ->orderBy('m.id', 'ASC')
                ->getQuery();

            $marci_db = $qmarca->getResult();


            $base_url = 'http://www.autokarma.ro/ajax/ajax_socket';
            if ($marci_db) {
                $nr = 1;

                foreach ($marci_db as $marca_db) {

                    $qm = $em->createQueryBuilder();
                    $q = $qm->select(array('mod'))
                        ->from('AppBundle:Model', 'mod')
                        ->where('mod.isActive = 1')
                        ->andWhere("mod.createdAt < '$current_date'")
                        ->andWhere('mod.marca = ' . $marca_db->getId())
                        ->orderBy('mod.id', 'ASC')
                        ->getQuery();

                    $modele_db = $q->getResult();
                    //$modele_db = $em->getRepository('AppBundle:Model')->findBy(array('marca' => $marca_db));

                    if (count($modele_db)) {
                        foreach ($modele_db as $id_model => $model_db) {
                            $post_string = 'id=' . str_replace('+', '%2B', $model_db->getPieseAutoProRemoteId()) . '&request=pop_models&selectSet=2';
                            $caroserii = $this->postCurl($base_url, $post_string);

                            preg_match_all('/value="(.*?)"/', $caroserii, $caroserii_matches, PREG_SET_ORDER);
                            preg_match_all('/<option(.*?)\s*>(.*?)<\/option>/', $caroserii, $caroserii_name_matches, PREG_SET_ORDER);

                            foreach ($caroserii_matches as $idcm => $caroserie_match) {
                                $this->addCaroserie($caroserie_match, $caroserii_name_matches, $idcm, $marca_db, $model_db, $output, $nr);
                                unset($caroserie_match[1]);
                                unset($caroserii_name_matches[$idcm]);
                            }


                            $model_db->setChecked(true);
                            $model_db->setCreatedAt(new \DateTime('now'));
                            $em->persist($model_db);
                            $em->flush();
                            unset($model_db);
                            unset($modele_db[$id_model]);
                        }
                    }
                    $marca_db->setChecked(true);
                    $marca_db->setCreatedAt(new \DateTime('now'));
                    $em->persist($marca_db);
                    $em->flush();
                    unset($marca_db);
                }
            }
        }
        /**
         * AUTOHUT
         */
        /*if($marci_db) {
            $nr = 1;
            foreach($marci_db as $marca_db) {
                $modele_db = $em->getRepository('AppBundle:Model')->findBy(array('marca' => $marca_db, 'id' => 109 ));

                if($modele_db) {
                    foreach($modele_db as $model_db) {
                        $ani_url = 'https://www.autohut.ro/ajax-search-tecdoc?brand='.$marca_db->getRemoteId().'&model='.$model_db->getRemoteId().'&year=0&body=0&fuel=0';
                        // get ani block
                        $get_ani = $this->getCurl($ani_url);



                        if(isset($get_ani->years)) {
                            $ani = (array)$get_ani->years;
                            foreach($ani as $an) {
                                $bodies_url = 'https://www.autohut.ro/ajax-search-tecdoc?brand='.$marca_db->getRemoteId().'&model='.$model_db->getRemoteId().'&year='.$an.'&body=0&fuel=0';
                                // get bodies block
                                $get_bodies = $this->getCurl($bodies_url);

                                if(isset($get_bodies->bodies)) {
                                    $bodies = (array)$get_bodies->bodies;
                                    foreach($bodies as $body_id => $body) {
                                        $fuels_url = 'https://www.autohut.ro/ajax-search-tecdoc?brand=' . $marca_db->getRemoteId() . '&model=' . $model_db->getRemoteId() . '&year=' . $an . '&body='.$body_id.'&fuel=0';
                                        // get bodies block
                                        $get_fuels = $this->getCurl($fuels_url);

                                        if(isset($get_fuels->fuels)) {
                                            $fuels = (array)$get_fuels->fuels;
                                            foreach($fuels as $fuel_id => $fuel) {
                                                $motorizari_url = 'https://www.autohut.ro/ajax-search-tecdoc?brand=' . $marca_db->getRemoteId() . '&model=' . $model_db->getRemoteId() . '&year=' . $an . '&body=' . $body_id . '&fuel='.$fuel_id;
                                                // get bodies block
                                                $get_motorizari = $this->getCurl($motorizari_url);

                                                if(isset($get_motorizari->engineList)) {
                                                    foreach($get_motorizari->engineList as $motorizare) {
                                                        $years = explode(' - ', $motorizare->years);
                                                        $power = explode(' ', $motorizare->power);

                                                        $exist = $em->getRepository('AppBundle:Motorizare')->findOneBy(array(
                                                            'codMotor' => $motorizare->code,
                                                            'caroserie' => $motorizare->body,
                                                            'cmc' => $motorizare->ccm,
                                                            'litri' => $motorizare->liters
                                                        ));

                                                        if(!$exist) {
                                                            $new = new Motorizare();
                                                            $new->setModel($model_db);
                                                            $new->setNume($motorizare->name);
                                                            if (isset($years[0]))
                                                                $new->setAnStart($years[0]);
                                                            if (isset($years[1]))
                                                                $new->setAnFinal($years[1]);
                                                            if (isset($power[0]))
                                                                $new->setKw((int)$power[0]);
                                                            if (isset($power[1]))
                                                                $new->setCp((int)$power[1]);
                                                            $new->setCmc($motorizare->ccm);
                                                            $new->setLitri($motorizare->liters);
                                                            $new->setCaroserie($motorizare->body);
                                                            $new->setCodMotor($motorizare->code);
                                                            $new->setCarburant(ucfirst(trim(str_replace('Motor', '', $fuel))));
                                                            $new->setIsActive(true);
                                                            $em->persist($new);
                                                            $em->flush();
                                                            $output->writeln(
                                                                sprintf(
                                                                    '<info>%s</info>. S-au adaugat date <info>%s</info>, Cod Motor <info>%s</info>', $nr, $motorizare->name, $motorizare->code));
                                                            $nr++;
                                                            unset($new);
                                                        }
                                                    }
                                                }
                                            }
                                            unset($fuels);
                                            $fuels = false;
                                        }
                                    }
                                    unset($bodies);
                                    $bodies = false;
                                }
                            }
                            unset($ani);
                            $ani = false;
                        }
                        $model_db->setChecked(true);
                        $em->persist($model_db);
                        $em->flush();
                        unset($model_db);
                    }
                }
            }
        }*/
        /**
         * END AUTOHUT
         */
    }

    public function makeSlugs($em, $output) {
        $motorizari = $em->getRepository('AppBundle:Motorizare')->findBy(array('slug' => null));
        /*$qm = $em->createQueryBuilder();
        $q = $qm->select(array('m.id, m.nume'))
            ->from('AppBundle:Motorizare', 'm')
            ->where('m.isActive = 1')
            ->orderBy('m.id', 'ASC')
            ->getQuery();

        $motorizari = $q->getResult();*/
        if(count($motorizari)) {
            foreach($motorizari as $motorizare) {
                $slug = UtilsTool::makeMotorizareSlug($motorizare->getNume()).'-'.$motorizare->getId();
                $motorizare->setSlug($slug);
                $em->persist($motorizare);
                $em->flush();
                $output->writeln(
                    sprintf(
                        '<error>%s</error>. S-a creat pentru motorizare <info>%s</info>, slug <info>%s</info>', $motorizare->getId(), $motorizare->getNume(), $slug));

            }
        }


    }

    public function addCaroserie($caroserie_match, $caroserii_name_matches, $idcm, $marca_db, $model_db, $output, $nr) {
        if ($caroserie_match[1] != '0') {
            $caroserie_name = $caroserii_name_matches[$idcm][2];
            $caroserie_id = $caroserie_match[1];

            $post_string = 'id=' . str_replace(array('+', '#'), array('%2B', '%23'), $caroserie_id) . '&request=pop_caroserie&selectSet=2';

            $ani = $this->postCurl($this->baseUrl(), $post_string);
            preg_match_all('/value="(.*?)"/', $ani, $ani_matches, PREG_SET_ORDER);
            preg_match_all('/<option(.*?)\s*>(.*?)<\/option>/', $ani, $ani_name_matches, PREG_SET_ORDER);

            foreach ($ani_matches as $idam => $an_match) {
                $this->addAn($an_match, $ani_name_matches, $idam, $marca_db, $model_db, $caroserie_name, $output, $nr);

                unset($an_match);
                unset($ani_matches[$idam]);
            }
        }
    }

    public function addAn($an_match, $ani_name_matches, $idam, $marca_db, $model_db, $caroserie_name, $output, $nr) {
        if ($an_match[1] != '0') {
            $an_id = $an_match[1];
            $an_name = $ani_name_matches[$idam][2];
            $post_string = 'id=' . str_replace(array('+', '#'), array('%2B', '%23'), $an_id) . '&request=pop_anfabric&selectSet=2';

            $combustibili = $this->postCurl($this->baseUrl(), $post_string);
            preg_match_all('/value="(.*?)"/', $combustibili, $combustibili_matches, PREG_SET_ORDER);
            preg_match_all('/<option(.*?)\s*>(.*?)<\/option>/', $combustibili, $combustibili_name_matches, PREG_SET_ORDER);

            foreach ($combustibili_matches as $idcombm => $combustibil_match) {
                $this->addCombustibil($combustibil_match, $combustibili_name_matches, $idcombm, $marca_db, $model_db, $caroserie_name, $output, $nr, $an_name);

                unset($combustibil_match);
                unset($combustibili_matches[$idcombm]);
            }
        }
    }

    public function addCombustibil($combustibil_match, $combustibili_name_matches, $idcombm, $marca_db, $model_db, $caroserie_name, $output, $nr, $an_name) {
        if ($combustibil_match[1] != '0') {
            $combustibil_name = $combustibili_name_matches[$idcombm][2];
            $combustibil_id = $combustibil_match[1];
            $post_string = 'id=' . str_replace(array('+', '#'), array('%2B', '%23'), $combustibil_id) . '&request=pop_combustibil&selectSet=2';
            try {
                $cmcs = $this->postCurl($this->baseUrl(), $post_string);
            } catch (Exception $e) {
                echo "CMC ";
                print_r($e->getMessage());
                exit;
            }
            preg_match_all('/value="(.*?)"/', $cmcs, $cmcs_matches, PREG_SET_ORDER);
            preg_match_all('/<option(.*?)\s*>(.*?)<\/option>/', $cmcs, $cmcs_name_matches, PREG_SET_ORDER);

            foreach ($cmcs_matches as $idcmcm => $cmc_match) {
                $this->addCmc($cmc_match, $cmcs_name_matches, $idcmcm, $marca_db, $model_db, $caroserie_name, $combustibil_name, $output, $nr, $an_name);
                unset($cmc_match);
                unset($cmcs_matches[$idcmcm]);
            }
        }
    }

    public function addCmc($cmc_match, $cmcs_name_matches, $idcmcm, $marca_db, $model_db, $caroserie_name, $combustibil_name, $output, $nr, $an_name) {
        if ($cmc_match[1] != '0') {
            $cmc_name = str_replace(' cmc', '', $cmcs_name_matches[$idcmcm][2]);
            $cmc_id = $cmc_match[1];
            $post_string = 'id=' . str_replace(array('+', '#'), array('%2B', '%23'), $cmc_id) . '&request=pop_capacitate&selectSet=2';
            try {
                $puteri = $this->postCurl($this->baseUrl(), $post_string);
            } catch (Exception $e) {
                echo "Puteri ";
                print_r($e->getMessage());
                exit;
            }
            preg_match_all('/value="(.*?)"/', $puteri, $puteri_matches, PREG_SET_ORDER);
            preg_match_all('/<option(.*?)\s*>(.*?)<\/option>/', $puteri, $puteri_name_matches, PREG_SET_ORDER);

            foreach ($puteri_matches as $idpm => $putere_match) {
                if ($putere_match[1] != '0') {
                    $this->addPutere($putere_match, $puteri_name_matches, $idpm, $marca_db, $model_db, $caroserie_name, $cmc_name, $combustibil_name, $output, $nr, $an_name);
                }
                unset($putere_match);
                unset($puteri_matches[$idpm]);
            }
        }
    }

    public function addPutere($putere_match, $puteri_name_matches, $idpm, $marca_db, $model_db, $caroserie_name, $cmc_name, $combustibil_name, $output, $nr, $an_name) {

        $putere_name = $puteri_name_matches[$idpm][2];
        $putere_id = $putere_match[1];
        $post_string = 'request=pop_putere&id=' . str_replace(array('+', '#'), array('%2B', '%23'), $putere_id) . '&selectSet=2';
        try {
            $motorizari = $this->postCurl($this->baseUrl(), $post_string);
        } catch (Exception $e) {
            echo "Motorizari ";
            print_r($e->getMessage());
            exit;
        }
        preg_match_all('/value="(.*?)"/', $motorizari, $motorizari_matches, PREG_SET_ORDER);
        preg_match_all('/<option(.*?)\s*>(.*?)<\/option>/', $motorizari, $motorizari_name_matches, PREG_SET_ORDER);
        foreach ($motorizari_matches as $idmotm => $motorizare_match) {
            $this->addMotorizare($motorizare_match, $motorizari_name_matches, $idmotm, $marca_db, $model_db, $putere_name, $caroserie_name, $cmc_name, $combustibil_name, $output, $nr, $an_name);
            $nr++;
            unset($motorizare_match);
            unset($motorizari_matches[$idmotm]);
        }


    }

    public function addMotorizare($motorizare_match, $motorizari_name_matches, $idmotm, $marca_db, $model_db, $putere_name, $caroserie_name, $cmc_name, $combustibil_name, $output, $nr, $an_name) {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        if ($motorizare_match[1] != '0') {
            $motorizare_name = $motorizari_name_matches[$idmotm][2];
            //$motorizare_id = $motorizare_match[1];

            $detalii_motorizare = explode('|', $motorizare_name);

            $motorizare_nume = trim($detalii_motorizare[1]) . ' ' . trim($detalii_motorizare[3]);
            $count_detalii_motorizare = count($detalii_motorizare);
            $cod_motor = trim($detalii_motorizare[$count_detalii_motorizare-2]);
            if($marca_db->getSlug() == 'bmw') {
                $bmw_name = explode(' ', trim($detalii_motorizare[1]));
                if(isset($bmw_name[1])) {
                    $motorizare_nume = trim($bmw_name[1]) . ' - ' . trim($detalii_motorizare[3]);
                } else {
                    $motorizare_nume = trim($detalii_motorizare[1]);
                }

            }

            $years_first = explode('-', $detalii_motorizare[2]);
            $year_from_first = explode('.', $years_first[0]);
            $year_from = trim($year_from_first[1]);
            $year_last_first = explode('.', $years_first[1]);

            $year_last = isset($year_last_first[1]) ? trim($year_last_first[1]) : date('Y');
            $putere_first = explode('/', $putere_name);
            $putere_kw = trim(str_replace('KW', '', $putere_first[0]));
            $putere_cp = trim(str_replace('CP', '', $putere_first[1]));


            $write_title = 'S-au MODIFICAT date ';

            $exist = $em->getRepository('AppBundle:Motorizare')->findOneBy(array(
                //'codMotor' => $cod_motor,
                'caroserie' => $caroserie_name,
                'cmc' => $cmc_name,
                'anStart' => $year_from,
                'anFinal' => $year_last,
                'kw' => (int)$putere_kw,
                'cp' => (int)$putere_cp,
                'nume' => $motorizare_nume,
            ));


            if($exist !== null) {
                $output->writeln(
                    sprintf(
                        '<error>%s</error>. '.$write_title.'<info>%s</info>, Cod Motor <info>%s</info>, an <info>%s</info>, <error>%s</error>', $marca_db->getNume(), $motorizare_nume, $cod_motor, $an_name, $model_db->getId()));

            } else {

                $write_title = 'S-au adaugat date ';

                $new = new Motorizare();
                $new->setModel($model_db);
                $new->setNume($motorizare_nume);
                $new->setAnStart($year_from);
                $new->setAnFinal($year_last);
                $new->setKw((int)$putere_kw);
                $new->setCp((int)$putere_cp);
                $new->setCmc($cmc_name);
                //$new->setLitri('');
                $new->setCaroserie($caroserie_name);
                $new->setCodMotor($cod_motor);
                $new->setCarburant($combustibil_name);
                $new->setIsActive(true);
                $em->persist($new);
                $em->flush();
                $output->writeln(
                    sprintf(
                        '<info>%s</info>. '.$write_title.'<info>%s</info>, Cod Motor <info>%s</info>, <error>%s</error>', $nr, $motorizare_nume, $cod_motor, $model_db->getId()));


            }
            unset($new);
            unset($exist);


        }
    }

    public function baseUrl() {
        return 'http://www.autokarma.ro/ajax/ajax_socket';
    }

    public function postCurl($url, $fields) {
        $cookie_file_path = getcwd().'/src/AppBundle/Command/'."cookies.txt";
        // verify if cookie file is accessible and writable
        if (! file_exists($cookie_file_path) || ! is_writable($cookie_file_path))
        {
            echo 'Cookie file missing or not writable.';
            exit;
        }

        // Initiate connection
        $ch = curl_init();
        // Set cURL and other options
        curl_setopt($ch, CURLOPT_URL, $url); // set url
        curl_setopt($ch, CURLOPT_POST, true);  // tell curl you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // define what you want to post
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); // set browser/user agent
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // automatically follow Location: headers (ie redirects)
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // auto set the referer in the event of a redirect
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // make sure we dont get stuck in a loop
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); // 10s timeout time for cURL connection
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // allow https verification if true
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // check common name and verify with host name
        curl_setopt($ch, CURLOPT_SSLVERSION,3); // verify ssl version 2 or 3
        curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "VeriSignClass3PublicPrimaryCertificationAuthority-G5.pem"); // allow ssl cert direct comparison
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE); // set new cookie session
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path); // file to save cookies in
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path); // file to read cookies in




        $output = curl_exec ($ch); // execute

        curl_close ($ch); // close curl handle

        return $output; // show output
    }

    public function getCurl($url, $method = 'GET') {

        //turn error reporting on
        error_reporting(E_ALL); ini_set("display_errors", 1);
        // cookie file name/location
        $cookie_file_path = getcwd().'/src/AppBundle/Command/'."cookies.txt";
        // verify if cookie file is accessible and writable
        if (! file_exists($cookie_file_path) || ! is_writable($cookie_file_path))
        {
            echo 'Cookie file missing or not writable.';
            exit;
        }

        // Initiate connection
        $ch = curl_init();
        // Set cURL and other options
        curl_setopt($ch, CURLOPT_URL, $url); // set url
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); // set browser/user agent
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // automatically follow Location: headers (ie redirects)
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // auto set the referer in the event of a redirect
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // make sure we dont get stuck in a loop
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); // 10s timeout time for cURL connection
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // allow https verification if true
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // check common name and verify with host name
        curl_setopt($ch, CURLOPT_SSLVERSION,3); // verify ssl version 2 or 3
        curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "VeriSignClass3PublicPrimaryCertificationAuthority-G5.pem"); // allow ssl cert direct comparison
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE); // set new cookie session
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path); // file to save cookies in
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path); // file to read cookies in

        // grab URL
        $response = curl_exec($ch);
        $err_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // close cURL connection, save cookie file, free up system resources
        curl_close($ch);

        switch($err_code) {
            case '200':
                return json_decode($response);
                break;
            default:
                return json_decode($response, true);
                break;
        }
    }

}
