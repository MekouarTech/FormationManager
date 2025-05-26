<?php

require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/database.php';

class CoursDALImpl implements ICoursDal
{
    private $pdo;

    public function __construct($db)
    {
        $this->pdo = $db;
    }

    public function create(Cours $cours)
    {
        $sql = "INSERT INTO cours (name, content, description, audience, duration, testIncluded, testContent, logo, sujetId)
                VALUES (:name, :content, :description, :audience, :duration, :testIncluded, :testContent, :logo, :sujetId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $cours->name);
        $stmt->bindValue(':content', $cours->content);
        $stmt->bindValue(':description', $cours->description);
        $stmt->bindValue(':audience', $cours->audience);
        $stmt->bindValue(':duration', $cours->duration, PDO::PARAM_INT);
        $stmt->bindValue(':testIncluded', $cours->testIncluded, PDO::PARAM_BOOL);
        $stmt->bindValue(':testContent', $cours->testContent);
        $stmt->bindValue(':logo', $cours->logo, PDO::PARAM_LOB);
        $stmt->bindValue(':sujetId', $cours->sujetId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM cours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Cours(
                $row['id'],
                $row['name'],
                $row['content'],
                $row['description'],
                $row['audience'],
                $row['duration'],
                $row['testIncluded'],
                $row['testContent'],
                $row['logo'],
                $row['sujetId']
            );
        }
        return null;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM cours";
        $stmt = $this->pdo->query($sql);
        $coursList = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $coursList[] = new Cours(
                $row['id'],
                $row['name'],
                $row['content'],
                $row['description'],
                $row['audience'],
                $row['duration'],
                $row['testIncluded'],
                $row['testContent'],
                $row['logo'],
                $row['sujetId']
            );
        }
        return $coursList;
    }

    public function update(Cours $cours)
    {
        $sql = "UPDATE cours SET name = :name, content = :content, description = :description, audience = :audience,
                duration = :duration, testIncluded = :testIncluded, testContent = :testContent, logo = :logo, sujetId = :sujetId
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $cours->name);
        $stmt->bindValue(':content', $cours->content);
        $stmt->bindValue(':description', $cours->description);
        $stmt->bindValue(':audience', $cours->audience);
        $stmt->bindValue(':duration', $cours->duration, PDO::PARAM_INT);
        $stmt->bindValue(':testIncluded', $cours->testIncluded, PDO::PARAM_BOOL);
        $stmt->bindValue(':testContent', $cours->testContent);
        $stmt->bindValue(':logo', $cours->logo, PDO::PARAM_LOB);
        $stmt->bindValue(':sujetId', $cours->sujetId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $cours->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM cours WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function isUsedInFormations($coursId)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM formation WHERE coursId = ?");
        $stmt->execute([$coursId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getCount()
    {
        $sql = "SELECT COUNT(*) FROM cours";
        $stmt = $this->pdo->query($sql);
        return (int)$stmt->fetchColumn();
    }

}
?>
