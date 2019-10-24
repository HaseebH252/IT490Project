<?php

require_once __DIR__ . '/vendor/autoload.php';
include ('rmq.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQAuthClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $login;
    private $corr_id;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            '$host',
            '$port',
            '$username',
            '$password'
        );
        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            "",
            false,
            false,
            true,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            '',
            false,
            true,
            false,
            false,
            array(
                $this,
                'onResponse'
            )
        );
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function auth($email, $password)
    {
        $this->response = null;
	$this->corr_id = uniqid();
	$input["Type"] ="Login";
	$input["email"]=$email;
	$input["pass"]=$password;

        $msg = new AMQPMessage(
            json_encode($input),
	    array(    
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            )
        );
        $this->channel->basic_publish($msg, '', 'authenticate');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    }


    public function reg($email, $password, $fname, $lname)
    {
        $this->response = null;
        $this->corr_id = uniqid();
	$input["Type"] ="Register";
	$input["firstname"] = $fname;
	$imput["lastname"] = $lname;
	$input["email"]=$email;
	$input["pass"]=$password;
	

        $msg = new AMQPMessage(
            json_encode($input),
            array(
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            )
        );
        $this->channel->basic_publish($msg, '', 'registration');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    }











}

echo ' [.] Got ', $response, "\n";
?>

