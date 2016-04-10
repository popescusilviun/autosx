<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Toolkit\Tools\UtilsTool;

class FrontendController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $marci = $em->getRepository('AppBundle:Marca')->findBy(array('isActive' => true), array('nume' => 'ASC'));

        $car_selected = UtilsTool::getAppCookie($request, $em, 'car_selected');
        $response = UtilsTool::updateResponse($car_selected);

        // replace this example code with whatever you need
        return $this->render('frontend/index.html.twig', [
            'marci' => $marci,
            'response' => $response,
            'homepage' => true
        ]);
    }

    /**
     * @Route("/load-products", name="piese_auto_load_products")
     */
    public function loadProductsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $car_selected = UtilsTool::getAppCookie($request, $em, 'car_selected');
        $response = UtilsTool::updateResponse($car_selected);

        $id_categorie = $request->get('id_category');

        $produse = array();
        $subcategories = array();
        if($id_categorie) {
            $motorizare = $em->getRepository('AppBundle:Motorizare')->find($response['tip_selected']);
            $categorie = $em->getRepository('AppBundle:Category')->find($id_categorie);
            $subcategories = $em->getRepository('AppBundle:Category')->findBy(array('parent' => $categorie));
            $produse = $em->getRepository('AppBundle:ProdusCategorieMotorizare')->findBy(array('categorie' => $categorie, 'motorizare' => $motorizare));

            if ($id_categorie) {
                $response['id_categorie'] = $id_categorie;
            }
            if ($categorie->getParent()) {
                $subcategory = $categorie->getParent();
                if ($subcategory->getParent()) {
                    $response['parent_category'] = $subcategory->getParent()->getId();
                } else {
                    $response['parent_category'] = $subcategory->getId();
                }
            }
            $response = UtilsTool::updateAppCookie($request, $em, 'car_selected', $response);
        }
        return $this->render('frontend/load_products.html.twig', [
            'produse' => $produse,
            'subcategories' => $subcategories,
            'response' => $response
        ]);
    }

    /**
     * @Route("/piese-auto/{marca_slug}-{model_slug}-{motorizare}/{id_categorie}", name="piese_auto_marca_model_motorizare_categorie")
     */
    public function pieseAutoMarcaModelMotorizareCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $car_selected = UtilsTool::getAppCookie($request, $em, 'car_selected');
        $response = UtilsTool::updateResponse($car_selected);
        $id_categorie = $request->get('id_categorie') ? $request->get('id_categorie') : $response['id_categorie'];

        $marci = $em->getRepository('AppBundle:Marca')->findBy(array('isActive' => true), array('nume' => 'ASC'));
        $motorizare = $em->getRepository('AppBundle:Motorizare')->find($response['tip_selected']);
        $categorie = $em->getRepository('AppBundle:Category')->find($id_categorie);
        $subcategories = $em->getRepository('AppBundle:Category')->findBy(array('parent' => $categorie));
        $produse = $em->getRepository('AppBundle:ProdusCategorieMotorizare')->findBy(array('categorie' => $categorie, 'motorizare' => $motorizare));

        $response['id_categorie'] = $id_categorie;
        if($categorie->getParent()) {
            $subcategory = $categorie->getParent();
            if($subcategory->getParent()) {
                $response['parent_category'] = $subcategory->getParent()->getId();
            } else {
                $response['parent_category'] = $subcategory->getId();
            }
        }

        $response = UtilsTool::updateAppCookie($request, $em, 'car_selected', $response);


        if(isset($response->tip) && !$response->tip) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render('frontend/index.html.twig', [
            'produse' => $produse,
            'response' => $response,
            'marci' => $marci,
            'subcategories' => $subcategories
        ]);
    }

    /**
     * @Route("/piese-auto/{marca_slug}-{model_slug}-{motorizare}", name="piese_auto_marca_model_motorizare")
     */
    public function pieseAutoMarcaModelMotorizareAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $car_selected = UtilsTool::getAppCookie($request, $em, 'car_selected');
        $response = UtilsTool::updateResponse($car_selected);

        $conn = $this->container->get('database_connection');
        $marci = $em->getRepository('AppBundle:Marca')->findBy(array('isActive' => true), array('nume' => 'ASC'));

        $slug_marca = $request->get('marca_slug', false);
        $slug_model = $request->get('model_slug', false);
        $slug_motorizare = $request->get('motorizare', false);
        if($slug_marca && !$response['marca']) {
            $response['marca'] = $em->getRepository('AppBundle:Marca')->findArrayMarcaBySlug($conn, $slug_marca);
            if($response['marca']) {
                $response['modele'] = $em->getRepository('AppBundle:Model')->findArrayModele($conn, $response['marca']['id']);
            }
        }

        if($slug_model && !$response['model']) {
            if($response['marca'] instanceof \stdClass) {
                $id_marca = $response['marca']->id;
            } else {
                $id_marca = $response['marca']['id'];
            }
            $response['model'] = $em->getRepository('AppBundle:Model')->findArrayModelByMarcaAndSlug($conn, $id_marca, $slug_model);
        }

        if($response['model']) {
            if($response['model'] instanceof \stdClass) {
                $id_model = $response['model']->id;
            } else {
                $id_model = $response['model']['id'];
            }
            $response['caroserii'] = $em->getRepository('AppBundle:Motorizare')->findArrayCaroserii($conn, $id_model);
        }
        /*if($response['an_selected'] && $response['combustibil_selected'] && !$response['motorizari']) {
            $response['motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariCombustibilAn($conn, $id_model, $response['an_selected'], $response['combustibil_selected']);
        }*/

        if($response['combustibil_selected']) {
            $response['cmcs'] = $em->getRepository('AppBundle:Motorizare')->findArrayCmcs($conn, $id_model, $response['caroserie_selected'], $response['an_selected'], $response['combustibil_selected']);
        }

        if($response['cmc_selected'] && !$response['puteri']) {
            $response['puteri'] = $em->getRepository('AppBundle:Motorizare')->findArrayPuteri($conn, $id_model, $response['caroserie_selected'], $response['an_selected'], $response['combustibil_selected'], $response['cmc_selected']);
        }

        if($response['putere_selected'] && !$response['motorizari']) {
            $response['motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariLast($conn, $id_model, $response['caroserie_selected'], $response['an_selected'], $response['combustibil_selected'], $response['cmc_selected'], $response['putere_selected']);
        }

        if($response['tip_selected'] && !$response['tip']) {
            $response['tip'] = $em->getRepository('AppBundle:Motorizare')->findOneArray($conn, $response['tip_selected']);
        } else if(!$response['tip_selected'] && $slug_motorizare) {
            $motorizare = $em->getRepository('AppBundle:Motorizare')->findOneBySlug($slug_motorizare);
            $response['tip_selected'] = $motorizare->getId();
            $response['tip'] = $em->getRepository('AppBundle:Motorizare')->findOneArray($conn, $response['tip_selected']);
        }
        $response['id_categorie'] = false;
        $response = UtilsTool::updateAppCookie($request, $em, 'car_selected', $response);

        return $this->render('frontend/index.html.twig', [
            'marci' => $marci,
            'response' => $response
        ]);
    }

    /**
     * @Route("/piese-auto/{marca_slug}-{model_slug}", name="piese_auto_marca_model")
     */
    public function pieseAutoMarcaModelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $car_selected = UtilsTool::getAppCookie($request, $em, 'car_selected');
        $response = UtilsTool::updateResponse($car_selected);

        $conn = $this->container->get('database_connection');
        $marci = $em->getRepository('AppBundle:Marca')->findBy(array('isActive' => true), array('nume' => 'ASC'));

        $slug_marca = $request->get('marca_slug', false);
        $slug_model = $request->get('model_slug', false);
        if($slug_marca && !$response['marca']) {
            $response['marca'] = $em->getRepository('AppBundle:Marca')->findArrayMarcaBySlug($conn, $slug_marca);
            if($response['marca']) {
                $response['modele'] = $em->getRepository('AppBundle:Model')->findArrayModele($conn, $response['marca']['id']);
            }
        }

        if($slug_model && !$response['model']) {
            if($response['marca'] instanceof \stdClass) {
                $id_marca = $response['marca']->id;
            } else {
                $id_marca = $response['marca']['id'];
            }
            $response['model'] = $em->getRepository('AppBundle:Model')->findArrayModelByMarcaAndSlug($conn, $id_marca, $slug_model);
        }

        if($response['model']) {
            if($response['model'] instanceof \stdClass) {
                $id_model = $response['model']->id;
            } else {
                $id_model = $response['model']['id'];
            }
            $response['caroserii'] = $em->getRepository('AppBundle:Motorizare')->findArrayCaroserii($conn, $id_model);
            /*$response['motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizari($conn, $id_model);
            $response['ani_motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMinMaxAniMotorizari($conn, $id_model);*/
        }
        if($response['an_selected'] && $response['combustibil_selected']) {
            $response['motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariCombustibilAn($conn, $id_model, $response['an_selected'], $response['combustibil_selected']);
        }



        return $this->render('frontend/index.html.twig', [
            'marci' => $marci,
            'response' => $response
        ]);
    }

    /**
     * @Route("/piese-auto/{marca_slug}", name="piese_auto_marca")
     */
    public function pieseAutoMarcaAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $car_selected = UtilsTool::getAppCookie($request, $em, 'car_selected', 'marca');
        $response = UtilsTool::updateResponse($car_selected);

        $conn = $this->container->get('database_connection');
        $marci = $em->getRepository('AppBundle:Marca')->findBy(array('isActive' => true), array('nume' => 'ASC'));

        $slug_marca = $request->get('marca_slug', false);
        if($slug_marca && !$response['marca']) {
            $response['marca'] = $em->getRepository('AppBundle:Marca')->findArrayMarcaBySlug($conn, $slug_marca);
            if($response['marca']) {
                $response['modele'] = $em->getRepository('AppBundle:Model')->findArrayModele($conn, $response['marca']['id']);
            }
        }

        $response = UtilsTool::updateAppCookie($request, $em, 'car_selected', $response);
        // replace this example code with whatever you need
        return $this->render('frontend/index.html.twig', [
            'marci' => $marci,
            'response' => $response
        ]);
    }

    /**
     * @Route("/ajax/set-user-options", name="ajax_set_user_options")
     */
    public function setUserOptionsAction(Request $request)
    {
        $id_marca = $request->get('marca_id', false);
        $id_model = $request->get('model_id', false);
        $an_selected = $request->get('an_selected', false);
        $caroserie_selected = $request->get('caroserie_selected', false);
        $combustibil_selected = $request->get('combustibil_selected', false);
        $cmc_selected = $request->get('cmc_selected', false);
        $putere_selected = $request->get('putere_selected', false);
        $tip_selected = $request->get('tip', false);
        if($tip_selected) {
            $id_marca = $request->get('marci', false);
            $id_model = $request->get('model', false);
            $an_selected = $request->get('an', false);
            $caroserie_selected = $request->get('caroserie', false);
            $combustibil_selected = $request->get('combustibil', false);
            $cmc_selected = $request->get('cmc', false);
            $putere_selected = $request->get('cp', false);
        }


        $em = $this->getDoctrine()->getManager();
        $conn = $this->container->get('database_connection');

        $response = array();
        if ($id_marca) {
            $response['marca'] = $em->getRepository('AppBundle:Marca')->findArrayMarca($conn, $id_marca);
            $response['modele'] = $em->getRepository('AppBundle:Model')->findArrayModele($conn, $id_marca);
        }

        if ($id_model) {
            $response['model'] = $em->getRepository('AppBundle:Model')->findArrayModel($conn, $id_model);
            $response['caroserii'] = $em->getRepository('AppBundle:Motorizare')->findArrayCaroserii($conn, $id_model);
            /*
            $response['motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizari($conn, $id_model);*/
        }

        if($caroserie_selected) {
            $response['ani_motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMinMaxAniMotorizari($conn, $id_model, $caroserie_selected);
            $response['caroserie_selected'] = $caroserie_selected;
        }

        if($an_selected) {
            //$response['combustibili'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariCombustibili($conn, $id_model, $response['caroserie_selected'], $an_selected);
            $response['combustibili'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariCombustibili($conn, $id_model, $response['caroserie_selected'], $an_selected);
            $response['an_selected'] = $an_selected;
        }

        if($combustibil_selected) {
            //$response['combustibili'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariCombustibili($conn, $id_model, $response['caroserie_selected'], $an_selected);
            $response['cmcs'] = $em->getRepository('AppBundle:Motorizare')->findArrayCmcs($conn, $id_model, $response['caroserie_selected'], $response['an_selected'], $combustibil_selected);
            $response['combustibil_selected'] = $combustibil_selected;
        }

        if($cmc_selected) {
            //$response['combustibili'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariCombustibili($conn, $id_model, $response['caroserie_selected'], $an_selected);
            $response['puteri'] = $em->getRepository('AppBundle:Motorizare')->findArrayPuteri($conn, $id_model, $response['caroserie_selected'], $response['an_selected'], $response['combustibil_selected'], $cmc_selected);
            $response['cmc_selected'] = $cmc_selected;
        }

        if($putere_selected) {
            $response['motorizari'] = $em->getRepository('AppBundle:Motorizare')->findArrayMotorizariLast($conn, $id_model, $response['caroserie_selected'], $response['an_selected'], $response['combustibil_selected'], $response['cmc_selected'], $putere_selected);
            $response['putere_selected'] = $putere_selected;
        }

        if($tip_selected) {
            $response['tip'] = $em->getRepository('AppBundle:Motorizare')->findOneArray($conn, $tip_selected);
            $response['tip_selected'] = $tip_selected;
        }

        //punem pe cookie selectia userului
        UtilsTool::updateAppCookie($request, $em, 'car_selected', $response);
        //UtilsTool::setAppCookie("car_selected", $cookie_values);

        if($tip_selected) {
            return $this->redirectToRoute('piese_auto_marca_model_motorizare', array('marca_slug' => $response['marca']['slug'], 'model_slug' => $response['model']['slug'], 'motorizare' => $response['tip']['slug']), 301);
        } else {
            return new JsonResponse($response);
        }
    }

    public function leftSideCategoriesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $car_selected = UtilsTool::getAppCookie($request, $em, 'car_selected');
        $response = UtilsTool::updateResponse($car_selected);
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('frontend/left_side_categories.html.twig', [
            'response' => $response,
            'categories' => $categories
        ]);

    }
}
