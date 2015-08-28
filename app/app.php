<?php


    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";


    use Symfony\Component\Debug\Debug;
    Debug::enable();

    $app = new Silex\Application();
    $app['debug'] = true;
    $server = 'mysql:host=localhost;dbname=shoe_store';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/brands", function() use ($app) {
        return $app['twig']->render('brands.html.twig', array('brands' => Brand::getAll()));
    });

    $app->get("/stores", function() use ($app) {
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    $app->post("/brands", function() use ($app) {
        $brandname = $_POST['brand'];
        $brand = new Brand($brandname);
        $brand->save();
        return $app['twig']->render('brands.html.twig', array('brands' => Brand::getAll()));
    });

    $app->post("/delete_stores", function() use ($app){
            Store::deleteAll();
            return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    $app->get("/brand/{id}", function($id) use ($app) {
        $brand = Brand::find($id);
        return $app['twig']->render('brand.html.twig', array('brand' => $brand, 'stores' => $brand->getStores(), 'all_stores' => Store::getAll()));
    });

    $app->post("/stores", function() use ($app) {
        $store = new Store($_POST['name']);
        $store->save();
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    $app->get("/store/{id}", function($id) use ($app) {
        $store = Store::find($id);
        $brands = $store->getBrands();
        return $app['twig']->render('store.html.twig', array('store' => $store, 'brands' => $brands, 'all_brands' => Brand::getAll()));
    });

    $app->post("/add_store", function() use ($app) {
        $brand = Brand::find($_POST['brand_id']);
        $store = Store::find($_POST['store_id']);
        $brand->addStore($store);
        return $app['twig']->render('brand.html.twig', array('brand' => $brand, 'stores' => $brand->getStores(), 'all_stores' => Store::getAll()));
    });

    $app->post("/add_brand", function() use ($app) {
        $brand = Brand::find($_POST['brand_id']);
        $store = Store::find($_POST['store_id']);
        $store->addBrand($brand);
        return $app['twig']->render('store.html.twig', array('store' => $store, 'brands' => $store->getBrands(), 'all_brands' => Brand::getAll()));
    });


    $app->post("/delete_store", function() use ($app){
        $store = Store::find($_POST['store_id']);
        $store->delete();
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    $app->patch("/update_store", function() use ($app) {
        $store = Store::find($_POST['store_id']);
        $store->update($_POST['name']);
        $brands = $store->getBrands();
        return $app['twig']->render('store.html.twig', array('store' => $store, 'brands' => $brands, 'all_brands' => Brand::getAll()));
        });


    return $app;

?>
