<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Messages as Message;
use Statbus\Models\Player as Player;

class MessageController extends Controller {
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->messageModel = new Message($this->container->get('settings')['statbus']);
    $this->pm = new Player($this->container->get('settings')['statbus']);
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_messages.id) FROM tbl_messages WHERE tbl_messages.deleted = 0
      AND (tbl_messages.expire_timestamp > NOW() OR tbl_messages.expire_timestamp IS NULL)") / $this->per_page);
    $this->url = $this->router->pathFor('message.index');
  }

  public function getAdminMemos(){
    $memos = $this->DB->run("SELECT
      M.id,
      M.type,
      M.adminckey,
      M.text,
      M.timestamp,
      M.server,
      M.round_id AS round,
      M.server_port AS port,
      M.lasteditor,
      A.rank as adminrank
      FROM tbl_messages AS M
      LEFT JOIN tbl_admin AS A ON M.adminckey = A.ckey

      WHERE M.deleted = 0
      AND M.type = 'memo'
      ORDER BY M.timestamp DESC");
    foreach ($memos as $m) {
      $m = $this->messageModel->parseMessage($m);
      $m->admin = new \stdclass;
      $m->admin->ckey = $m->adminckey;
      $m->admin->rank = $m->adminrank;
      $m->admin = $this->pm->parsePlayer($m->admin);
    }
    return $memos;
  }

  public function listing($request, $response, $args){
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $messages = $this->DB->run("SELECT
      M.id,
      M.type,
      M.adminckey,
      M.targetckey,
      M.text,
      M.timestamp,
      M.server,
      M.round_id AS round,
      M.server_port AS port,
      M.lasteditor,
      M.severity,
      M.expire_timestamp AS expire,
      M.secret,
      A.rank as adminrank,
      T.rank as targetrank,
      E.rank as editorrank
      FROM tbl_messages AS M
      LEFT JOIN tbl_admin AS A ON M.adminckey = A.ckey
      LEFT JOIN tbl_admin AS T ON M.targetckey = T.ckey
      LEFT JOIN tbl_admin AS E ON M.lasteditor = E.ckey
      WHERE M.deleted = 0
      AND (M.expire_timestamp > NOW() OR M.expire_timestamp IS NULL)
      ORDER BY M.timestamp DESC
      LIMIT ?,?", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($messages as $m) {
      $m = $this->messageModel->parseMessage($m);
      $m->admin = new \stdclass;
      $m->admin->ckey = $m->adminckey;
      $m->admin->rank = $m->adminrank;
      $m->admin = $this->pm->parsePlayer($m->admin);

      $m->target = new \stdclass;
      $m->target->ckey = $m->targetckey;
      $m->target->rank = $m->targetrank;
      $m->target = $this->pm->parsePlayer($m->target);

      $m->editor = new \stdclass;
      $m->editor->ckey = $m->lasteditor;
      $m->editor->rank = $m->editorrank;
      $m->editor = $this->pm->parsePlayer($m->editor);
    }
    return $this->view->render($this->response, 'messages/listing.tpl',[
      'messages' => $messages,
      'message'  => $this
    ]);
  }

  public function getMessagesForCkey($ckey, $hide_secret = false, $page = 1){
    $secret = "";
    if($hide_secret){
      $secret = "AND M.SECRET = 0";
    }
    $this->page = filter_var($page, FILTER_VALIDATE_INT);
    $this->pages = ceil($this->DB->cell("SELECT count(M.id) FROM tbl_messages M WHERE M.deleted = 0
      AND (M.expire_timestamp > NOW() OR M.expire_timestamp IS NULL)
      AND M.targetckey = ?
      $secret", $ckey) / $this->per_page);
    $this->url = $this->router->pathFor('player.messages',['ckey'=>$ckey]);
    $messages = $this->DB->run("SELECT
      M.id,
      M.type,
      M.adminckey,
      M.targetckey,
      M.text,
      M.timestamp,
      M.server,
      M.round_id AS round,
      M.server_port AS port,
      M.lasteditor,
      M.severity,
      M.expire_timestamp AS expire,
      M.secret,
      A.rank as adminrank,
      T.rank as targetrank,
      E.rank as editorrank
      FROM tbl_messages AS M
      LEFT JOIN tbl_admin AS A ON M.adminckey = A.ckey
      LEFT JOIN tbl_admin AS T ON M.targetckey = T.ckey
      LEFT JOIN tbl_admin AS E ON M.lasteditor = E.ckey
      WHERE M.deleted = 0
      AND (M.expire_timestamp > NOW() OR M.expire_timestamp IS NULL)
      AND M.targetckey = ?
      $secret
      ORDER BY M.timestamp DESC
      LIMIT ?,?", $ckey,
        ($this->page * $this->per_page) - $this->per_page,
        $this->per_page);
    foreach ($messages as $m) {
      $m = $this->messageModel->parseMessage($m);
      $m->admin = new \stdclass;
      $m->admin->ckey = $m->adminckey;
      $m->admin->rank = $m->adminrank;
      $m->admin = $this->pm->parsePlayer($m->admin);

      $m->target = new \stdclass;
      $m->target->ckey = $m->targetckey;
      $m->target->rank = $m->targetrank;
      $m->target = $this->pm->parsePlayer($m->target);

      $m->editor = new \stdclass;
      $m->editor->ckey = $m->lasteditor;
      $m->editor->rank = $m->editorrank;
      $m->editor = $this->pm->parsePlayer($m->editor);
    }
    return $messages;
  }

  public function single($request, $response, $args){
    $id = filter_var($args['id'], FILTER_VALIDATE_INT);
    $message = $this->DB->row("SELECT
      M.id,
      M.type,
      M.adminckey,
      M.targetckey,
      M.text,
      M.timestamp,
      M.server,
      M.round_id AS round,
      M.server_port AS port,
      M.lasteditor,
      M.severity,
      M.edits,
      M.expire_timestamp AS expire,
      M.secret,
      A.rank as adminrank,
      T.rank as targetrank,
      E.rank as editorrank
      FROM tbl_messages AS M
      LEFT JOIN tbl_admin AS A ON M.adminckey = A.ckey
      LEFT JOIN tbl_admin AS T ON M.targetckey = T.ckey
      LEFT JOIN tbl_admin AS E ON M.lasteditor = E.ckey
      WHERE M.id = ?
      ORDER BY M.timestamp DESC", $id);
    $message = $this->messageModel->parseMessage($message);

    $message->admin = new \stdclass;
    $message->admin->ckey = $message->adminckey;
    $message->admin->rank = $message->adminrank;
    $message->admin = $this->pm->parsePlayer($message->admin);

    $message->target = new \stdclass;
    $message->target->ckey = $message->targetckey;
    $message->target->rank = $message->targetrank;
    $message->target = $this->pm->parsePlayer($message->target);

    $message->editor = new \stdclass;
    $message->editor->ckey = $message->lasteditor;
    $message->editor->rank = $message->editorrank;
    $message->editor = $this->pm->parsePlayer($message->editor);

    return $this->view->render($this->response, 'messages/single.tpl',[
      'message' => $message
    ]);
  }
}