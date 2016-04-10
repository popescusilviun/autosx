<?php

namespace AppBundle\Command;

use AppBundle\Entity\Model;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Toolkit\Tools\UtilsTool;

/**
 * @author Silviu Popescu <popescu.silviun@gmail.com>
 */
class FindModeleCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('find:modele')
            ->setDescription('Find Modele on website')
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

        $qb = $em->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Marca', 'm')
            ->where('m.remoteId IS NOT NULL');
        $marci_db = $qb->getQuery()->getResult();

        if($marci_db) {
            foreach($marci_db as $marca_db) {
                //$modele_url = 'https://www.autohut.ro/ajax-search-tecdoc?brand='.$marca_db->getRemoteId().'&model=0&year=0&body=0&fuel=0';
                $modele_url = 'http://www.autokarma.ro/ajax/ajax_socket';
                $post_string = 'id='.$marca_db->getRemoteId().'&request=pop_brands';
                $modele = $this->postCurl($modele_url, $post_string);
                preg_match_all('/value="(.*?)"/', $modele, $matches, PREG_SET_ORDER);
                foreach($matches as $match) {
                    if($match[1] != '0') {
                        $name = explode('++', $match[1]);
                        $model_name = $name[1];
                        $model_id = $match[1];


                        try {
                            $model_db = $em->getRepository('AppBundle:Model')->findOneBy(array('pieseAutoProRemoteId' => $model_id));
                        }
                        catch(\Doctrine\ORM\NoResultException $e) {
                            $model_db = new Model();
                        }
                        if(!$model_db) {
                            $model_db = new Model();
                        }

                        $slug = UtilsTool::makeSlug($model_name);
                        try {
                            $model_db->setNume($model_name);
                            $model_db->setPieseAutoProRemoteId($model_id);
                            $model_db->setMarca($marca_db);
                            $model_db->setSlug($slug);
                            $model_db->setIsActive(true);
                            $em->persist($model_db);
                            $em->flush();
                        } catch ( \Symfony\Component\Debug\Exception\FatalThrowableError $e) {
                            var_dump($e->getMessage());
                            exit;
                        }
                        $output->writeln(
                            sprintf(
                                'Marca <info>%s</info>, Model <info>%s</info>, remote_id <info>%s</info>', $marca_db->getNume(), $model_name, $model_id));
                    }
                }

                /*$modele_url = 'http://www.pieseautopro.ro/shop/car_selector?car_make='.$marca_db->getRemoteId();
                // get modele block
                $get_modele = $this->getCurl($modele_url);
                $modele = (array)$get_modele;

                foreach($modele as $model_id => $model_name) {
                    try {
                        $model_db = $em->getRepository('AppBundle:Model')->findOneBy(array('pieseAutoProRemoteId' => $model_id));
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $model_db = new Model();
                    }
                    if(!$model_db) {
                        $model_db = new Model();
                    }

                    $slug = UtilsTool::makeSlug($model_name);
                    try {
                        $model_db->setNume($model_name);
                        $model_db->setPieseAutoProRemoteId($model_id);
                        $model_db->setMarca($marca_db);
                        $model_db->setSlug($slug);
                        $model_db->setIsActive(true);
                        $em->persist($model_db);
                        $em->flush();
                    } catch ( \Symfony\Component\Debug\Exception\FatalThrowableError $e) {
                        var_dump($e->getMessage());
                        exit;
                    }
                    $output->writeln(
                        sprintf(
                            'Marca <info>%s</info>, Model <info>%s</info>, remote_id <info>%s</info>', $marca_db->getNume(), $model_name, $model_id));
                }*/
                /**
                 * AUTOHUT
                 */
                /*
                if($get_modele->models) {
                    $modele = (array)$get_modele->models;
                    foreach($modele as $model_id => $model_name) {
                        try {
                            $model_db = $em->getRepository('AppBundle:Model')->findOneBy(array('remoteId' => $model_id));
                        }
                        catch(\Doctrine\ORM\NoResultException $e) {
                            $model_db = new Model();
                        }
                        if(!$model_db) {
                            $model_db = new Model();
                        }

                        $slug = UtilsTool::makeSlug($model_name);
                        try {
                            $model_db->setNume($model_name);
                            $model_db->setRemoteId($model_id);
                            $model_db->setMarca($marca_db);
                            $model_db->setSlug($slug);
                            $model_db->setIsActive(true);
                            $em->persist($model_db);
                            $em->flush();
                        } catch ( \Symfony\Component\Debug\Exception\FatalThrowableError $e) {
                            var_dump($e->getMessage());
                            exit;
                        }
                        $output->writeln(
                            sprintf(
                                'Marca <info>%s</info>, Model <info>%s</info>, remote_id <info>%s</info>', $marca_db->getNume(), $model_name, $model_id));
                    }
                    $marca_db->setChecked(true);
                    $em->persist($marca_db);
                    $em->flush();
                }*/
                /**
                 * END AUTOHUT
                 */
            }
        }

    }

    public function postCurl($url, $fields) {
        $ch = curl_init();                    // initiate curl

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);  // tell curl you want to post something
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // define what you want to post
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
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
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); // set browser/user agent
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // automatically follow Location: headers (ie redirects)
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // auto set the referer in the event of a redirect
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // make sure we dont get stuck in a loop
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10s timeout time for cURL connection
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
