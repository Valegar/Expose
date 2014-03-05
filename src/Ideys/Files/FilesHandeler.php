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
     * Save a file on database.
     *
     * @param \Ideys\Files\File
     */
    public function addFile(File $file)
    {
        $file->persist();

        $timestamp = (new \DateTime())->format('c');

        $this->db->insert('expose_files', array(
            'file' => $file->getFileName(),
            'mime' => $file->getMime(),
            'title' => $file->getTitle(),
            'name' => $file->getName(),
            'slug' => $file->getSlug(),
            'updated_at' => $timestamp,
            'created_at' => $timestamp,
        ));
        $file->setId($this->db->lastInsertId());

        $recipient = new Recipient();
        $recipient->setName('URL');
        $recipient->setFile($file);
        $this->addRecipient($recipient);
    }

    /**
     * Save a file recipient on database.
     *
     * @param \Ideys\Files\Recipient
     */
    public function addRecipient(Recipient $recipient)
    {
        $this->db->insert('expose_files_recipients', array(
            'expose_files_id' => $recipient->getFile()->getId(),
            'name' => $recipient->getName(),
            'token' => $recipient->getToken(),
            'download_counter' => $recipient->getDownloadCounter(),
            'download_logs' => serialize($recipient->getDownloadLogs()),
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
        return 'SELECT f.id, f.file, f.mime, f.title, f.name, f.slug, '
             . 'r.id AS rid, r.name AS recipient, r.token, '
             . 'r.download_counter, r.download_logs '
             . 'FROM expose_files AS f '
             . 'LEFT JOIN expose_files_recipients AS r '
             . 'ON f.id = r.expose_files_id ';
    }

    /**
     * Retrieve all files.
     */
    public function findAll()
    {
        $entities = $this->db->fetchAll(
                $this->baseQuery()
        );

        $files = array();
        foreach ($entities as $entity) {
            $files[$entity['id']] = $this->hydrateFile($entity);
        }
        foreach ($entities as $entity) {
            $this->insertRecipient($files[$entity['id']], $entity);
        }

        return $files;
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
        $entity = $this->db->fetchAssoc(
                $this->baseQuery()
              . 'WHERE f.slug = ?'
              . 'AND r.token = ?',
        array($slug, $token));

        return $this->hydrateFile($entity);
    }

    /**
     * Hydrate a File with db results.
     *
     * @param array $entity
     *
     * @return \Ideys\Files\File
     */
    private function hydrateFile($entity)
    {
        $file = new File();
        $file
            ->setId($entity['id'])
            ->setFileName($entity['file'])
            ->setMime($entity['mime'])
            ->setName($entity['name'])
            ->setTitle($entity['title'])
            ->setSlug($entity['slug'])
        ;
        return $file;
    }

    /**
     * Insert a recipient to a file.
     *
     * @param \Ideys\Files\File $file
     * @param array             $entity
     *
     * @return \Ideys\Files\File
     */
    private function insertRecipient(File $file, $entity)
    {
        $recipient = new Recipient();
        $recipient
            ->setId($entity['rid'])
            ->setName($entity['recipient'])
            ->setToken($entity['token'])
            ->setDownloadCounter($entity['download_counter'])
            ->setDownloadLogs($entity['download_logs'])
        ;

        return $file->addRecipient($recipient);
    }
}
