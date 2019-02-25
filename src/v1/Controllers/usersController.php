<?php
namespace App\v1\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Users;
/**
 * Controller v1 de usuários
 */
class UsersController {
    /**
     * Container Class
     * @var [object]
     */
    private $container;
    /**
     * Undocumented function
     * @param [object] $container
     */
    public function __construct($container) {
        $this->container = $container;
    }
    /**
     * Listagem de usuários
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function listUser($request, $response, $args) {
        $entityManager = $this->container->get('em');
        $usersRepository = $entityManager->getRepository('App\Models\Entity\Users');
        $users = $usersRepository->findAll();
        $return = $response->withJson($users, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;        
    }
    /**
     * Cria um usuário
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function createUser($request, $response, $args) {
        $params = (object) $request->getParams();
        /**
         * Pega o Entity Manager do nosso Container
         */
        $entityManager = $this->container->get('em');
        /**
         * Instância da nossa Entidade preenchida com nossos parametros do post
         */
        $users = (new User())->setName($params->name);
        /**
         * Registra a criação do usuário
         */
        $logger = $this->container->get('logger');
        $logger->info('User Created!', $users->getValues());
        /**
         * Persiste a entidade no banco de dados
         */
        $entityManager->persist($users);
        $entityManager->flush();
        $return = $response->withJson($users, 201)
            ->withHeader('Content-type', 'application/json');
        return $return;       
    }
    /**
     * Exibe as informações de um usuário
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function viewUser($request, $response, $args) {
        $id = (int) $args['id'];
        $entityManager = $this->container->get('em');
        $usersRepository = $entityManager->getRepository('App\Models\Entity\Users');
        $users = $usersRepository->find($id); 
        /**
         * Verifica se existe um usuário com a ID informada
         */
        if (!$users) {
            $logger = $this->container->get('logger');
            $logger->warning("User {$id} Not Found");
            throw new \Exception("User not Found", 404);
        }    
        $return = $response->withJson($users, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;   
    }
    /**
     * Atualiza um usuário
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function updateUser($request, $response, $args) {
        $id = (int) $args['id'];
        /**
         * Encontra o usuário no banco
         */ 
        $entityManager = $this->container->get('em');
        $usersRepository = $entityManager->getRepository('App\Models\Entity\Users');
        $users = $usersRepository->find($id);   
        /**
         * Verifica se existe um usuário com a ID informada
         */
        if (!$users) {
            $logger = $this->container->get('logger');
            $logger->warning("User {$id} Not Found");
            throw new \Exception("User not Found", 404);
        }  
        /**
         * Atualiza e persiste o usuário com os parâmetros recebidos no request
         */
        $users->setName($request->getParam('name'));
        /**
         * Persiste a entidade no banco de dados
         */
        $entityManager->persist($users);
        $entityManager->flush();        
        $return = $response->withJson($users, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;       
    }
    /**
     * Deleta um usuário
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function deleteUser($request, $response, $args) {
        $id = (int) $args['id'];
        /**
         * Encontra o usuário no banco
         */ 
        $entityManager = $this->container->get('em');
        $usersRepository = $entityManager->getRepository('App\Models\Entity\Users');
        $users = $usersRepository->find($id);   
        /**
         * Verifica se existe um usuário com a ID informada
         */
        if (!$users) {
            $logger = $this->container->get('logger');
            $logger->warning("User {$id} Not Found");
            throw new \Exception("User not Found", 404);
        }  
        /**
         * Remove a entidade
         */
        $entityManager->remove($users);
        $entityManager->flush(); 
        $return = $response->withJson(['msg' => "Deletando o usuário {$id}"], 204)
            ->withHeader('Content-type', 'application/json');
        return $return;    
    }
}