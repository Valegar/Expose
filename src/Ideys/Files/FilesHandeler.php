<?php

namespace Ideys\Files;

use Doctrine\DBAL\Connection;

/**
 * Downloadable files manager.
 */
class FilesHandeler
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $db;


    /**
     * Constructor.
     *
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * Save a message on database.
     *
     * @param Message
     */
    public function persist(Message $message)
    {
        $this->db->insert('expose_files', array(
            'name' => $message->getName(),
            'email' => $message->getEmail(),
            'subject' => $message->getSubject(),
            'message' => $message->getMessage(),
            'date' => $message->getDate()->format('Y-m-d H:i:s'),
        ));
    }

    /**
     * Delete a message.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db->delete('expose_files', array('id' => $id));
    }

    /**
     * Retrieve all files.
     *
     * @return string
     */
    private function baseQuery()
    {
        return 'SELECT * '
             . 'FROM expose_files AS f '
             . 'LEFT JOIN expose_files_recipients AS r '
             . 'ON f.id = r.expose_files_id ';
    }

    /**
     * Retrieve all files.
     */
    public function findAll()
    {
        $messages = $this->db->fetchAll(
                $this->baseQuery()
        );

        return $messages;
    }

    /**
     * Retrieve a file by its slug and recipient token.
     *
     * @param string $slug  The file url slug name.
     * @param string $token The recipient credential token.
     *
     * @return \Ideys\Files\File
     */
    public function findBySlugAndToken($slug, $token)
    {
        $result = $this->db->fetchAssoc(
                $this->baseQuery()
              . 'WHERE f.slug = ?'
              . 'AND r.token = ?',
        array($slug, $token));

        $file = new File($result);

        return $file;
    }
}
