<?php
// Routes
$data = file_get_contents(__DIR__ . "/employees.json");
$list = json_decode($data, true);

$app->get('/', function ($request, $response, $args) use ($list,$app) {
    $query=$request->getQueryParams()['query'];
    if(!is_null($query) && $query!=''){
        $key = array_search($query, array_column($list, 'email'));
        $list=[$list[$key]];
    }
    return $this->renderer->render($response, 'index.phtml', ['args' => $args,
        'data' => $list,
        "router" => $this->router]);
});

$app->get('/employee/{id}', function ($request, $response, $args) use ($list) {

    $key = array_search($request->getAttribute('id'), array_column($list, 'id'));

    return $this->renderer->render($response, 'detail.phtml', ['args' => $args,
        'result' => $list[$key]]);
})->setName("employee-detail");


//$app ->get('/search/{min}', function() use ($app,$request,$response) {
//    var_dump($request->getAttribute('min'));exit;
//    $articles = Model::factory('Article') -> order_by_desc('timestamp') -> find_many();
//
//    $app->response->headers->set('Content-Type', 'text/xml');
//
//  return $app -> render('rss.xml', array('articles' => $articles));
//})->setName("search");


$app->get("/search/{min}/{max}", function ($request,$response) use ($app,$list) {
//    $app->response()->header("Content-Type", "application/json");
    return $this->renderer->render($response, 'service.xml');
    echo json_encode($list);
})->setName('service');