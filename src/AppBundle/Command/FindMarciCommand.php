<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Toolkit\SimpleHtmlDom;

/**
 * @author Silviu Popescu <popescu.silviun@gmail.com>
 */
class FindMarciCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('find:marci')
            ->setDescription('Find Marci on website')
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

        $marci_url = 'https://www.autohut.ro/ajax-search-tecdoc?brand=0&model=0&year=0&body=0&fuel=0';
        // get marci block
        $get_marci = $this->getCurl($marci_url);
        if($get_marci) {
            foreach($get_marci->brands as $marci) {
                $nume_marca = $marci->name;
                if($nume_marca == 'MERCEDES') $nume_marca = 'Mercedes-Benz';
                if($nume_marca == 'VW') $nume_marca = 'Volkswagen';
                $output->writeln(
                    sprintf(
                        'Marca <info>%s</info>, este interogata', $nume_marca));

                $qb = $em->createQueryBuilder();
                $qb->select('m')
                    ->from('AppBundle:Marca', 'm')
                    ->where('LOWER(m.nume) = :nume')
                    //->andWhere('m.remoteId = :remote')
                    ->setParameter('nume', strtolower($nume_marca));
                    //->setParameter('remote', null);
                $marca_db = $qb->getQuery()->getSingleResult();

                if($marca_db && !$marca_db->getRemoteId()) {
                    $marca_db->setRemoteId($marci->brand_id);
                    try {
                        $em->persist($marca_db);
                        $em->flush();
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                    }
                    $output->writeln(
                        sprintf(
                            'Marca <info>%s</info>, a fost updatata cu remote_id <info>%s</info>', $marca_db->getNume(), $marci->brand_id));
                }

            }
        }
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
