<?php

namespace App\Api;

use App\Service\ClienteService;
use App\Validator;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ClienteApi
{

    protected $service;

    public function __construct(ClienteService $service)
    {
        $this->service = $service;
    }

    public function listar(Application $app)
    {
        return $app->json($this->service->findAllArray());
    }

    public function novo(Request $request, Application $app)
    {
        $dados = [
            "nome" => $request->get('nome'),
            "email" => $request->get('email'),
            "cpf" => $request->get('cpf'),
            "rg" => $request->get('rg'),
        ];
        $constraint = new Assert\Collection(array(
            'nome' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 3, 'max' => 255))
            ),
            'email' => array(
                new Assert\NotBlank(),
                new Assert\Email(),
            ),
            'cpf' => array(
                new Assert\NotBlank(),
                new Validator\Cpf,
            ),
            'rg' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 3))
            ),
        ));

        $errors = $app['validator']->validate($dados, $constraint);
        if (count($errors) > 0) {
            $arr_erros = [];
            foreach ($errors as $error) {
                $arr_erros[] = $error->getPropertyPath() . ' ' . $error->getMessage();
            }
            return new JsonResponse(array(
                'errors' => $arr_erros
            ));
        }

        try {
            $insert = $this->service->insert($dados);
            return $app->json(array('success' => true));
        } catch (Exception $ex) {
            return $app->json(array(
                        'success' => true,
                        'errors' => ['Erro durante o cadastro: ' . $ex->getMessage()]
            ));
        }
    }

    public function buscaPorId($id, Application $app)
    {
        $entity = $this->service->find($id);
        if (! $entity instanceof \App\Entity\Cliente) {
            return $app->json(array(
                        'errors' => ["Erro: Registro com ID = $id não existe!"]
            ));
        }
        return $app->json( $entity->toArray() );
    }

    public function editar(Request $request, $id, Application $app)
    {

        $entity = $this->service->find($id);
        if (is_null($entity)) {
            return $app->json(array(
                        'errors' => ["Erro: Registro com ID = $id não existe!"]
            ));
        }
        $dados = [
            "nome" => $request->get('nome'),
            "email" => $request->get('email'),
            "cpf" => $request->get('cpf'),
            "rg" => $request->get('rg'),
        ];
        $constraint = new Assert\Collection(array(
            'nome' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 3, 'max' => 255))
            ),
            'email' => array(
                new Assert\NotBlank(),
                new Assert\Email(),
            ),
            'cpf' => array(
                new Assert\NotBlank(),
                new Validator\Cpf(),
            ),
            'rg' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 3))
            ),
        ));

        $errors = $app['validator']->validate($dados, $constraint);
        if (count($errors) > 0) {
            $arr_erros = [];
            foreach ($errors as $error) {
                $arr_erros[] = $error->getPropertyPath() . ' ' . $error->getMessage();
            }
            return new JsonResponse(array(
                'success' => false,
                'errors' => $arr_erros
            ));
        }

        $dados['id'] = $id;

        try {
            $ret = $this->service->update($id, $dados);
            return $app->json(array('success' => true));
        } catch (Exception $ex) {
            return $app->json(array(
                        'success' => true,
                        'errors' => ['Erro durante a edição: ' . $ex->getMessage()]
            ));
        }
    }

    public function apagar($id, Application $app)
    {
        $entity = $this->service->find($id);
        if (is_null($entity)) {
            return $app->json(array(
                        'errors' => ["Erro: Registro com ID = $id não existe!"]
            ));
        }

        try {
            $ret = $this->service->delete($id);
            return $app->json(array('success' => true));
        } catch (Exception $ex) {
            return $app->json(array(
                        'success' => false,
                        'errors' => ['Erro durante a exclusão: ' . $ex->getMessage()]
            ));
        }
    }

}
