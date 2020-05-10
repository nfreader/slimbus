<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Library as Library;
use Statbus\Controllers\UserController as User;

class LibraryController Extends Controller {
  
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_library.id) FROM tbl_library WHERE tbl_library.content != ''
      AND (tbl_library.deleted IS NULL OR tbl_library.deleted = 0)") / $this->per_page);

    $this->libraryModel = new Library();
    $this->guzzle = $this->container->get('guzzle');

    $this->breadcrumbs['Library'] = $this->router->pathFor('library.index');
    $this->url = $this->router->pathFor('library.index');
    $this->query = false;
  }

  public function index($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $this->query = filter_var($request->getQueryParams()['query'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    if($this->query) {
      $statement = \ParagonIE\EasyDB\EasyStatement::open()->andWith('AND tbl_library.content like ?', '%'.$this->DB->escapeLikeValue($this->query).'%');
      $this->pages = ceil($this->DB->cell("SELECT
        count(tbl_library.id) 
        FROM tbl_library 
        WHERE tbl_library.content != ''
        AND (tbl_library.deleted IS NULL OR tbl_library.deleted = 0) 
        $statement", $statement->values()[0]) / $this->per_page);
      $books = $this->DB->run("SELECT 
        tbl_library.id,
        tbl_library.author,
        tbl_library.title,
        tbl_library.category,
        IF('Adult' = tbl_library.category, 1, 0) AS nsfw
        FROM tbl_library
        WHERE tbl_library.content != ''
        AND (tbl_library.deleted IS NULL OR tbl_library.deleted = 0)
        $statement
        ORDER BY tbl_library.datetime DESC
        LIMIT ?,?", $statement->values()[0], ($this->page * $this->per_page) - $this->per_page, $this->per_page);
      $this->search = $this->query;
      $this->query = "?query=$this->query";
    } else {
      $books = $this->DB->run("SELECT 
        tbl_library.id,
        tbl_library.author,
        tbl_library.title,
        tbl_library.category,
        IF('Adult' = tbl_library.category, 1, 0) AS nsfw
        FROM tbl_library
        WHERE tbl_library.content != ''
        AND (tbl_library.deleted IS NULL OR tbl_library.deleted = 0)
        ORDER BY tbl_library.datetime DESC
        LIMIT ?,?", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
      }
    foreach ($books as &$book) {
      $book = $this->libraryModel->parseBook($book);
    }
    return $this->view->render($response, 'library/listing.tpl',[
      'books'       => $books,
      'library'     => $this,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function single($request, $response, $args) {
    $book = $this->getBook($args['id']);
    return $this->view->render($response, 'library/single.tpl',[
      'book'       => $book,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata
    ]);
  }

  public function getBook(int $id){
    $id = filter_var($id, FILTER_VALIDATE_INT);
    $book = $this->DB->row("SELECT
      L.id,
      L.author,
      L.title,
      L.content,
      L.category,
      L.ckey,
      L.datetime,
      L.deleted,
      L.round_id_created,
      IF('Adult' = L.category, 1, 0) AS nsfw
      FROM tbl_library AS L
      WHERE L.id = ?", $id);
    $book = $this->libraryModel->parseBook($book);
    $url = parent::getFullURL($this->router->pathFor('library.single',['id'=>$book->id]));
    if(!$book->deleted) {
      $this->breadcrumbs[$book->title] = $url;
    } else {
      $this->breadcrumbs['[Book Deleted]'] = $url;
    }
    return $book;
  }

  public function deleteBook($request, $response, $args){
    $id = filter_var($args['id'], FILTER_VALIDATE_INT);
    $book = $this->getBook($id);
    $url = parent::getFullURL($this->router->pathFor('library.single',['id'=>$book->id]));
    if(FALSE === $request->getAttribute('csrf_status')){
      return $this->view->render($response, 'base/error.tpl',[
        'message'  => "CSRF failure. This action is denied.",
        'code'     => 403,
        'link'     => $url,
        'linkText' => 'Back'
      ]);
    }
    $user = (new User($this->container))->fetchUser();
    if (!$user->canAccessTGDB) {
      return $this->view->render($response, 'base/error.tpl',[
        'message' => "You do not have permission to access this page.",
        'code'    => 403
      ]);
    }
    $delete = TRUE;
    $action = 'F451';
    $text = "Deleted book $id";
    if($book->deleted){
      $delete = FALSE;
      $action = 'F452';
      $text = "Undeleted book $id";
    }
    $this->DB->update('tbl_library',[
      'deleted' => $delete
    ],[
      'id' => $id
    ]);
    $book = $this->getBook($id);
    (new StatbusController($this->container))->submitToAuditLog($action, $text);
    return $this->view->render($response, 'library/single.tpl',[
      'book'        => $book,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata
    ]);
  }

  public function artGallery($request, $response, $args){
    $servers = $this->container->get('settings')['statbus']['servers'];
    if(isset($args['server'])){
      $server = ucfirst($args['server']);
      if($server = $servers[array_search($server, array_column($servers,'name'))]){
        $json_url = str_replace('data/logs', 'data', $server['public_logs'].'paintings.json');
        try{
          $res = $this->guzzle->request('GET',$json_url);
        } catch (GCeption $e){
          return false;
        }
        $art = $res->getBody()->getContents();
        if(!$art){
          return false;
        }
        $art = json_decode($art);
        return $this->view->render($response, 'gallery/gallery.tpl',[
          'art' => $art,
          'url' => str_replace('data/logs/', 'data/paintings', $server['public_logs']),
          'server' => $server
        ]);
      }
    }
    return $this->view->render($response, 'gallery/index.tpl',[
      'servers' => $servers
    ]);
  }
}