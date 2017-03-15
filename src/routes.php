<?php

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



$app->get("/search/{min}/{max}", function ($request,$response) use ($app,$list) {

    foreach ($list as $k=>$v) {
        $salary = str_replace(",", "", str_replace("$", "", $v['salary']));

        if (!($salary >= $request->getAttribute('min') && $salary <= $request->getAttribute('max'))) {
            unset($list[$k]);
        }
    }
    $renderer = new RKA\ContentTypeRenderer\Renderer();
    $response = $renderer->render($request, $response, $list);
    $response = $response->withHeader('Content-type', 'text/xml');
    return $response->withStatus(200);
});
