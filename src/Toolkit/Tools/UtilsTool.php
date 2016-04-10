<?php
namespace Toolkit\Tools;

use AppBundle\Entity\UserCarSelected;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class UtilsTool
{
    const COOKIE_KEY = '05461ae42c85ff183e7623a1d674fd42';

    private static $conso=array("b","c","d","f","g","h","j","k","l",
        "m","n","p","r","s","t","v","w","x","y","z");

    private static $number = array(1,2,3,4,5,6,7,8,9);

    private static $vocal=array("a","e","i","o","u");
    /**
 * @return array euro
 */
    public function getEuros($euro = null, $slug = false, $notSlug = null, $onlySlug = null) {
        if($onlySlug && is_string($euro)) {
            return strtolower(str_replace(array('&', ' / ', ' '), array('_', '_', '_'), $euro));
        }
        if($notSlug) {
            return ucfirst(str_replace('_', ' ', $euro));//strtolower(str_replace(array('&', ' / '), array('_', '_'), $euro));
        }

        $euros = array(
            'Non euro' => 0,
            'Euro 1' => 1,
            'Euro 2' => 2,
            'Euro 3' => 3,
            'Euro 4' => 4,
            'Euro 5' => 5,
            'Euro 6' => 6
        );
        $eurosRetro = array(
            0 => 'Non euro',
            1 => 'Euro 1',
            2 => 'Euro 2',
            3 => 'Euro 3',
            4 => 'Euro 4',
            5 => 'Euro 5',
            6 => 'Euro 6'
        );

        if($euro && isset($eurosRetro[$euro])) {
            if($slug) {
                return strtolower(str_replace(array('&', ' / '), array('_', '_'), $eurosRetro[$euro]));
            }
            return $eurosRetro[$euro];
        }
        return $euros;
    }

    /**
     * @return array numar_locuri
     */
    public function getNumarLocuri($loc = null, $slug = false) {
        $locuri = array(
            '2' => 1,
            'de la 3 la 6' => 2,
            'peste 6' => 3
        );

        if($loc && isset($locuri[$loc])) {
            if($slug) {
                return strtolower(str_replace(array('&', ' / '), array('_', '_'), $locuri[$loc]));
            }
            return $locuri[$loc];
        }
        return $locuri;
    }

    /**
     * @return array euro
     */
    public function getTractiune() {
        $tractiune = array(
            'fata' => 'fata',
            'spate' => 'spate',
            'integrala' => 'integrala'
        );
        return $tractiune;
    }

    //array cu culoare
    public function getCuloare() {
        $culoare = array('alb' => 'alb', 'albastru' => 'albastru', 'argintiu' => 'argintiu', 'auriu' => 'auriu', 'bej' => 'bej', 'galben' => 'galben', 'gri' => 'gri', 'maro' => 'maro', 'negru' => 'negru', 'portocaliu' => 'portocaliu', 'rosu' => 'rosu', 'verde' => 'verde', 'violet' => 'violet', 'visiniu' => 'visiniu', 'alta' => 'alta');
        return $culoare;
    }

    /**
     * @return array numar_usi
     */
    public function getNumarUsi($usa = null, $slug = false) {
        $usi = array(
            '2 sau 3' => 1,
            '4 sau 5' => 2,
            'mai mult de 5' => 3
        );

        if($usa && isset($usi[$usa])) {
            if($slug) {
                return strtolower(str_replace(array('&', ' / '), array('_', '_'), $usi[$usa]));
            }
            return $usi[$usa];
        }
        return $usi;
    }

    /**
     * @return array years
     */
    public function getYears($year = false) {
        $years = array();
        for($i=date("Y"); $i>=1950; $i--) {
            $years[$i] = $i;
        }

        if($year && isset($years[$year])) {
            return $years[$year];
        }
        return $years;
    }

    /**
     * @return array combustibili
     */
    public static function getFuel($fuel = null, $slug = false) {
        $fuels = array(
            'Benzina' => 1,
            'Benzina & GPL' => 2,
            'Benzina & GNC' => 3,
            'Electric' => 4,
            'Hibrid' => 5,
            'Diesel / Motorina' => 6,
            'Hidrogen' => 7,
            'Etanol' => 8
        );

        if($fuel) {
            if($slug) {
                if(isset($fuels[$fuel])) {
                    return strtolower(str_replace(array('&', ' / '), array('_', '_'), $fuels[$fuel]));
                } else {
                    return false;
                }
            }
            if(isset($fuels[$fuel])) {
                return $fuels[$fuel];
            } else {
                return false;
            }
        }
        return $fuels;
    }

    /**
     * @return array combustibili by slug
     */
    public function getFuelBySlug($fuel_slug = null, $fuel = null, $onlySlug = null) {
        if($fuel) {
            $fuel_slug = strtolower(str_replace(array('&', ' / '), array('_', '_'), $fuel_slug));
        }
        if($onlySlug) {
            return $fuel_slug;
        }
        $fuels = array(
            'benzina' => 1,
            'benzina_gpl' => 2,
            'benzina_gnc' => 3,
            'electric' => 4,
            'hibrid' => 5,
            'diesel_motorina' => 6,
            'diesel' => 6,
            'motorina' => 6,
            'hidrogen' => 7,
            'etanol' => 8
        );

        if($fuel_slug ) {
            if(isset($fuels[$fuel_slug])) {
                return $fuels[$fuel_slug];
            } else {
                return false;
            }
        }
        return $fuels;
    }

    /**
     * @return array transmisie
     */
    public static function getTransmisie($transmisie = null, $slug = false) {
        $transmisii = array(
            'Automata' => 1,
            'Manuala' => 2,
            'Semi-automata / Secventiala' => 3,
        );
        $transmisiiRetro = array(
            1 => 'Automata',
            2 => 'Manuala',
            3 => 'Semi-automata / Secventiala',
        );

        if($transmisie !== null && isset($transmisiiRetro[$transmisie])) {
            if($slug) {
                return strtolower(str_replace(array('&', ' / '), array('_', '_'), $transmisiiRetro[$transmisie]));
            }
            return $transmisiiRetro[$transmisie];
        } else if ($transmisie === null) {
            return null;
        }
        return $transmisii;
    }

    /**
     * @return array stari
     */
    public static function getStare($stare = null, $slug = false) {
        $stari = array(
            'Avariat' => 1,
            'Neavariat' => 2,
            'Nou, nefolosit' => 3,
        );
        $stariRetro = array(
            1 => 'Avariat',
            2 => 'Neavariat',
            3 => 'Nou, nefolosit',
        );

        if($stare && isset($stariRetro[$stare])) {
            if($slug) {
                return strtolower(str_replace(array('&', ' / '), array('_', '_'), $stariRetro[$stare]));
            }
            return $stariRetro[$stare];
        }
        return $stari;
    }


    /**
     * @return array palette of colors
     */
    public static function getPaletteColors($selectedColor = false) {
        $palette = array(
            '#FFFFFF' => 'Alb',
            '#175A9E' => 'Albastru',
            '#517E23' => 'Verde',
            '#7DB9EF' => 'Bleu',
            '#fcdb22' => 'Galben',
            '#ea8c00' => 'Portocaliu',
            '#9b0005' => 'Rosu grena',
            '#c4001d' => 'Rosu',
            '#5e2f5a' => 'Violet inchis',
            '#aa003c' => 'Roz ( ciclam )',
            '#cba9bc' => 'Violet deschis',
            '#82658d' => 'Violet Lavanda',
            '#ffa4c4' => 'Roz deschis',
            '#310062' => 'King Blue',
            '#0086b3' => 'Ceruleum',
            '#009568' => 'Turquoise',
            '#006231' => 'Verde inchis',
            '#76c106' => 'Yellow Green',
            '#f4f4dc' => 'Galben discret',
            '#575757' => 'Gri inchis',
            '#aeb2b2' => 'Silver Gray',
            '#390205' => 'Maro inchis',
            '#cc6600' => 'Maro mediu',
            '#7b875f' => 'Kaki',
            '#bb8e77' => 'Maro deschis',
            '#94650e' => 'Auriu',
            '#5e2f5a' => 'Galben Yellow',
            '#000000' => 'Negru'
        );
        if($selectedColor) {
            return $palette[$selectedColor];
        }
        return $palette;
    }

    /**
     * @return array counties
     */
    public function getCounties($county = false) {
        $counties = array(
            "" => " - Alege - ",
            "alba" => "Alba",
            "arad" => "Arad",
            "arges" => "Arges",
            "bacau" => "Bacau",
            "bihor" => "Bihor",
            "bistrita-nasaud" => "Bistrita-Nasaud",
            "botosani" => "Botosani",
            "braila" => "Braila",
            "brasov" => "Brasov",
            "bucuresti" => "Bucuresti",
            "buzau" => "Buzau",
            "calarasi" => "Calarasi",
            "caras-severin" => "Caras-Severin",
            "cluj" => "Cluj",
            "constanta" => "Constanta",
            "covasna" => "Covasna",
            "dambovita" => "Dambovita",
            "dolj" => "Dolj",
            "galati" => "Galati",
            "giurgiu" => "Giurgiu",
            "gorj" => "Gorj",
            "harghita" => "Harghita",
            "hunedoara" => "Hunedoara",
            "ialomita" => "Ialomita",
            "iasi" => "Iasi",
            "ilfov" => "Ilfov",
            "maramures" => "Maramures",
            "mehedinti" => "Mehedinti",
            "mures" => "Mures",
            "neamt" => "Neamt",
            "olt" => "Olt",
            "prahova" => "Prahova",
            "salaj" => "Salaj",
            "satu-mare" => "Satu-Mare",
            "sibiu" => "Sibiu",
            "suceava" => "Suceava",
            "teleorman" => "Teleorman",
            "timis" => "Timis",
            "tulcea" => "Tulcea",
            "valcea" => "Valcea",
            "vaslui" => "Vaslui",
            "vrancea" => "Vrancea"
        );
        if($county) {
            return $counties[$county];
        }
        return $counties;
    }

    /**
     * @return array statuses
     */
    public function getOrderStatuses($status = '') {
        $statuses = array(
            0 => array(
                'label' => 'In cos client',
                'class' => 'status_black'
            ),
            1 => array(
                'label' => 'Comanda noua',
                'class' => 'status_red'
            ),
            2 => array(
                'label' => 'Comanda procesata',
                'class' => 'status_orange'
            ),
            3 => array(
                'label' => 'Comanda livrata',
                'class' => 'status_yellow'
            ),
            4 => array(
                'label' => 'Comanda finalizata',
                'class' => 'status_green'
            ),
            5 => array(
                'label' => 'Comanda anulata',
                'class' => 'status_black'
            )
        );
        if($status != '') {
            return $statuses[$status];
        }
        return $statuses;
    }

    public function productViewIncrement($request, $em, $obj) {
        $objects = array();
        $cookie = $request->cookies->get('cv_view');
        $setCookie = false;

        if($cookie) {
            $objects = unserialize($cookie);
            if(!in_array($obj->getId(), $objects)) {
                $objects[$obj->getId()] = $obj->getId();
                $setCookie = true;
            }
        } else {
            $objects[$obj->getId()] = $obj->getId();
        }

        if($setCookie) {
            $cookie = new Cookie('cv_view', serialize($objects), time() + 3600 * 12);
            $responseC = new Response();
            $responseC->headers->setCookie($cookie);
            $responseC->send();

            $obj->setVizualizari($obj->getVizualizari()+1);
            $em->persist($obj);
            $em->flush();
        }
        return;
    }

    public static function decodeCautareAvansata($str, $base=10, $chars='0123456789abcdefghijklmnopqrstuvwxyz') {
        $len = strlen($str);
        $val = 0;
        $arr = array_flip(str_split($chars));
        for($i = 0; $i < $len; ++$i) {
            $val += $arr[$str[$i]] * pow($base, $len-$i-1);
        }
        return $val;
    }

    //encode cautare_avansata numar
    public static function encodeCautareAvansata($val, $base=24, $chars='abcdefghijklmnopqrstuvwxyz') {
        // can't handle numbers larger than 2^31-1 = 2147483647
        $str = '';
        do {
            $i = $val % $base;
            $str = $chars[$i] . $str;
            $val = ($val - $i) / $base;
        } while($val > 0);
        return $str;
    }

    public static function generateUniqueCode($str) {
        $cnt = strlen($str);
        $semiCnt = abs($cnt/2);
        $val = substr($str, 0, 2) . substr($str, $semiCnt, 2) . substr($str, $cnt-2, 2);
        return $val;
    }

    public static function watermarkImage($watermark, $image) {
        $old_path = $image;

        ////////////////////water mark
        $main_image = imagecreatefromstring(file_get_contents(getcwd().'/'.$old_path));

// Load the logo image
        $logoImage = imagecreatefrompng($watermark);
        imagealphablending($logoImage, true);

// Get dimensions
        $imageWidth=imagesx($main_image);
        $imageHeight=imagesy($main_image);

        $logoWidth=imagesx($logoImage);
        $logoHeight=imagesy($logoImage);

// Paste the logo
        imagecopy(
        // source
            $main_image,
            // destination
            $logoImage,
            // destination x and y
            $imageWidth-$logoWidth, $imageHeight-$logoHeight,
            // source x and y
            0, 0,
            // width and height of the area of the source to copy
            $logoWidth, $logoHeight);
        ////////////////////////

        file_put_contents('/tmp/silviu.log', var_export($logoImage, 1)."\n\n", FILE_APPEND);

        //rename($old_path, $new_path);// save image
        imagejpeg($logoImage, $image);
    }

    /*
     * generatePass
     *
     * genereaza o parola human readeable, de o anumita lungime, cu numere/fara
     *
     * @params $iLenghtPass lungimea parolei, $bWithNumbers bool - contine numere sau nu, default false
     *
     * echo PasswordReadable::generatePass(8);
     *
     */
    public static function generatePass($iLenghtPass = 10, $bWithNumbers = false) {
        $password = '';
        srand ((double)microtime()*1000000);
        $max = $iLenghtPass/2;
        $password='';

        $conso = $bWithNumbers ? array_merge(self::$conso, self::$number) : self::$conso;
        $vocal = self::$vocal;

        $iConsoLenght = $bWithNumbers ? 27 : 19;

        for($i=1; $i<=$max; $i++)
        {
            $password.=$conso[rand(0,$iConsoLenght)];
            $password.=$vocal[rand(0,4)];
        }

        return trim($password);

    }

    public static function makeSlug($string) {
        $unique = strtolower(trim($string));
        $unique = str_replace(',', '', $unique);
        $unique = str_replace('.', '', $unique);
        $unique = str_replace('@', '', $unique);
        $unique = str_replace('#', '', $unique);
        $unique = str_replace('$', '', $unique);
        $unique = str_replace('%', '', $unique);
        $unique = str_replace('^', '', $unique);
        $unique = str_replace('&', '', $unique);
        $unique = str_replace('*', '', $unique);
        $unique = str_replace('(', '', $unique);
        $unique = str_replace(')', '', $unique);
        $unique = str_replace('"', '', $unique);
        $unique = str_replace('\'', '', $unique);
        $unique = str_replace('/', '', $unique);
        $unique = str_replace('\\', '', $unique);
        $unique = str_replace(':', '', $unique);
        $unique = str_replace(';', '', $unique);
        $unique = str_replace('>', '', $unique);
        $unique = str_replace('<', '', $unique);
        $unique = str_replace('!', '', $unique);
        $unique = str_replace('~', '', $unique);
        $unique = str_replace('`', '', $unique);
        $unique = str_replace('?', '', $unique);
        $unique = str_replace('!', '', $unique);
        $unique = str_replace('+', '', $unique);
        $unique = str_replace('-', '_', $unique);
        $unique = str_replace(' ', '_', $unique);

        return $unique;
    }

    public static function makeMotorizareSlug($string) {
        $unique = strtolower(trim($string));
        $unique = str_replace(',', '', $unique);
        $unique = str_replace('.', '', $unique);
        $unique = str_replace('@', '', $unique);
        $unique = str_replace('#', '', $unique);
        $unique = str_replace('$', '', $unique);
        $unique = str_replace('%', '', $unique);
        $unique = str_replace('^', '', $unique);
        $unique = str_replace('&', '', $unique);
        $unique = str_replace('*', '', $unique);
        $unique = str_replace('(', '', $unique);
        $unique = str_replace(')', '', $unique);
        $unique = str_replace('"', '', $unique);
        $unique = str_replace('\'', '', $unique);
        $unique = str_replace('/', '', $unique);
        $unique = str_replace('\\', '', $unique);
        $unique = str_replace(':', '', $unique);
        $unique = str_replace(';', '', $unique);
        $unique = str_replace('>', '', $unique);
        $unique = str_replace('<', '', $unique);
        $unique = str_replace('!', '', $unique);
        $unique = str_replace('~', '', $unique);
        $unique = str_replace('`', '', $unique);
        $unique = str_replace('?', '', $unique);
        $unique = str_replace('!', '', $unique);
        $unique = str_replace('+', '', $unique);
        //$unique = str_replace('-', '_', $unique);
        $unique = str_replace(' ', '-', $unique);

        return $unique;
    }

    public static function setAppCookie($cookie_name, $cookie_value, $cookie_time = null) {
        if(!$cookie_time) {
            $cookie_time = time()+3600 * 24 * 7;
        }
        //$cookie_value = base64_encode(json_encode($cookie_value));
        //$cookie_value = md5('car_selected_' . date("Y-m-d H:i:s"));
        $server_host = explode('.', $_SERVER['HTTP_HOST']);
        unset($server_host[0]);
        $host = implode('.', $server_host);

        setcookie($cookie_name, $cookie_value, $cookie_time, "/", $host, 0);
    }

    public static function getAppCookie($request, $em, $cookie_name, $resetWithout = false) {
        $cookie_value = $request->cookies->get($cookie_name);
        if(!$cookie_value) {
            $cookie_value = md5('car_selected_' . date("Y-m-d H:i:s"));
        }
        $user_car_selected = $em->getRepository('AppBundle:UserCarSelected')->findOneBy(array('cookie' => $cookie_value));
        if(!$user_car_selected) {
            $response = array();
            $response['marca'] = false;
            $response['model'] = false;
            $response['ani_motorizari'] = false;
            $response['an_selected'] = false;
            $response['combustibil_selected'] = false;
            $response['motorizare_selected'] = false;
            $response['motorizari'] = false;
            $response['combustibili'] = false;
            $response['modele'] = false;
            $response['caroserii'] = false;
            $response['caroserie_selected'] = false;
            $response['cmcs'] = false;
            $response['cmc_selected'] = false;
            $response['puteri'] = false;
            $response['putere_selected'] = false;
            $response['tipuri_auto'] = false;
            $response['tip_selected'] = false;
            $response['tip'] = false;
            $response['id_categorie'] = false;
            $response['parent_category'] = false;


            $user_car_selected = new UserCarSelected();
            $user_car_selected->setCookie($cookie_value);
            $user_car_selected->setCreatedAt(new \DateTime('now'));
            $user_car_selected->setCarData(json_encode($response));
            $em->persist($user_car_selected);
            $em->flush();
            self::setAppCookie($cookie_name, $cookie_value);
        }


        /*$response = new \stdClass();
        $response->marca = false;
        $response->model = false;
        $response->ani_motorizari = false;
        $response->an_selected = false;
        $response->modele = false;
        $response->motorizari = false;

        // Get cookie value

        if($cookie) {
            $response = json_decode(base64_decode($cookie));
            if($response) {
                if(!isset($response->marca)) $response->marca = false;
                if(!isset($response->model)) $response->model = false;
                if(!isset($response->ani_motorizari)) $response->ani_motorizari = false;
                if(!isset($response->an_selected)) $response->an_selected = false;
                if(!isset($response->modele)) $response->modele = false;
                if(!isset($response->motorizari)) $response->motorizari = false;
            }
        }
        return $response;*/
        $car_select = json_decode($user_car_selected->getCarData());
        if($resetWithout) {
            if($resetWithout == 'marca') {
                $car_select->model = false;
                $car_select->combustibili = false;
                $car_select->an_selected = false;
                $car_select->ani_motorizari = false;
                $car_select->motorizari = false;

                $car_select->combustibil_selected = false;
                $car_select->motorizare_selected = false;
                $response->caroserii = false;
                $response->caroserie_selected = false;
                $response->cmcs = false;
                $response->cmc_selected = false;
                $response->puteri = false;
                $response->putere_selected = false;
                $response->tipuri_auto = false;
                $response->tip_selected = false;
                $response->tip = false;
                $response->id_categorie = false;
                $response->parent_category = false;
            }
            if($resetWithout == 'default') {
                $car_select->marca = false;
                $car_select->modele = false;
                $car_select->model = false;
                $car_select->combustibili = false;
                $car_select->an_selected = false;
                $car_select->ani_motorizari = false;
                $car_select->motorizari = false;

                $car_select->combustibil_selected = false;
                $car_select->motorizare_selected = false;
                $response->caroserii = false;
                $response->caroserie_selected = false;
                $response->cmcs = false;
                $response->cmc_selected = false;
                $response->puteri = false;
                $response->putere_selected = false;
                $response->tipuri_auto = false;
                $response->tip_selected = false;
                $response->tip = false;
                $response->id_categorie = false;
                $response->parent_category = false;
            }
        }

        return $car_select;
    }

    public static function updateAppCookie($request, $em, $cookie_name, $data) {
        $cookie_value = $request->cookies->get($cookie_name);
        $car_selected = json_encode(array());
        if($cookie_value) {
            $user_car_selected = $em->getRepository('AppBundle:UserCarSelected')->findOneBy(array('cookie' => $cookie_value));
            if($user_car_selected) {
                $user_car_selected->setCarData(json_encode($data));
                $em->persist($user_car_selected);
                $em->flush();
                $car_selected = $user_car_selected->getCarData();
            }
        }
        return json_decode($car_selected);
    }

    public static function updateResponse($car_selected) {
        $response = array();
        $response['marca'] = isset($car_selected->marca) ? $car_selected->marca : false;
        $response['model'] = isset($car_selected->model) ? $car_selected->model : false;
        $response['combustibili'] = isset($car_selected->combustibili) ? $car_selected->combustibili : false;
        $response['an_selected'] = isset($car_selected->an_selected) ? $car_selected->an_selected : false;
        $response['ani_motorizari'] = isset($car_selected->ani_motorizari) ? $car_selected->ani_motorizari : false;
        $response['motorizari'] = isset($car_selected->motorizari) ? $car_selected->motorizari : false;
        $response['modele'] = isset($car_selected->modele) ? $car_selected->modele : false;
        $response['combustibil_selected'] = isset($car_selected->combustibil_selected) ? $car_selected->combustibil_selected : false;
        $response['motorizare_selected'] = isset($car_selected->motorizare_selected) ? $car_selected->motorizare_selected : false;

        $response['caroserii'] = isset($car_selected->caroserii) ? $car_selected->caroserii : false;
        $response['caroserie_selected'] = isset($car_selected->caroserie_selected) ? $car_selected->caroserie_selected : false;
        $response['cmcs'] = isset($car_selected->cmcs) ? $car_selected->cmcs : false;
        $response['cmc_selected'] = isset($car_selected->cmc_selected) ? $car_selected->cmc_selected : false;
        $response['puteri'] = isset($car_selected->puteri) ? $car_selected->puteri : false;
        $response['putere_selected'] = isset($car_selected->putere_selected) ? $car_selected->putere_selected : false;
        $response['tipuri_auto'] = isset($car_selected->tipuri_auto) ? $car_selected->tipuri_auto : false;
        $response['tip_selected'] = isset($car_selected->tip_selected) ? $car_selected->tip_selected : false;
        $response['tip'] = isset($car_selected->tip) ? $car_selected->tip : false;
        $response['id_categorie'] = isset($car_selected->id_categorie) ? $car_selected->id_categorie : false;
        $response['parent_category'] = isset($car_selected->parent_category) ? $car_selected->parent_category : false;

        return $response;
    }
}