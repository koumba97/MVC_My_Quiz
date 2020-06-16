<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616080717 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE question CHANGE id_categorie id_categorie INT NOT NULL, CHANGE question question VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT NOT NULL, CHANGE reponse reponse VARCHAR(255) NOT NULL, CHANGE reponse_expected reponse_expected INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie CHANGE name name VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE question CHANGE id_categorie id_categorie INT DEFAULT NULL, CHANGE question question VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE reponse CHANGE id_question id_question INT DEFAULT NULL, CHANGE reponse reponse VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`, CHANGE reponse_expected reponse_expected TINYINT(1) DEFAULT NULL');
    }
}
