<?php

namespace App\Services\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
// use Symfony\Component\Console\Output\ConsoleOutput;

class ChatOrder implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        // Initialize an array to hold the connected clients
        $this->clients = new \SplObjectStorage;
    }

    // Called when a client connects
    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->clients->attach($conn);
    }

    // Called when a client sends a message
    public function onMessage(ConnectionInterface $from, $msg)
    {
        // $output = new ConsoleOutput();
        // $output->writeln($from . ' ' . $msg);
        foreach ($this->clients as $client) {
            // Forward the message to all connected clients
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    // Called when a client disconnects
    public function onClose(ConnectionInterface $conn)
    {
        // Remove the connection from the storage
        $this->clients->detach($conn);
    }

    // Called if there is an error with a connection
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Log the error
        echo "Error: " . $e->getMessage() . "\n";

        // Close the connection
        $conn->close();
    }
}
