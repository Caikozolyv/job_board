<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725130543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_4C62E638BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, website_id INT NOT NULL, presence_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, creation_date DATE DEFAULT NULL, application_date DATE NOT NULL, salary INT DEFAULT NULL, asked_salary INT DEFAULT NULL, city VARCHAR(255) NOT NULL, status INT NOT NULL, INDEX IDX_FBD8E0F818F45C82 (website_id), INDEX IDX_FBD8E0F8F328FFC4 (presence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_action (job_id INT NOT NULL, action_id INT NOT NULL, INDEX IDX_17C8A113BE04EA9 (job_id), INDEX IDX_17C8A1139D32F035 (action_id), PRIMARY KEY(job_id, action_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presence (id INT AUTO_INCREMENT NOT NULL, presence VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE website (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F818F45C82 FOREIGN KEY (website_id) REFERENCES website (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8F328FFC4 FOREIGN KEY (presence_id) REFERENCES presence (id)');
        $this->addSql('ALTER TABLE job_action ADD CONSTRAINT FK_17C8A113BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_action ADD CONSTRAINT FK_17C8A1139D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638BE04EA9');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F818F45C82');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8F328FFC4');
        $this->addSql('ALTER TABLE job_action DROP FOREIGN KEY FK_17C8A113BE04EA9');
        $this->addSql('ALTER TABLE job_action DROP FOREIGN KEY FK_17C8A1139D32F035');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE job_action');
        $this->addSql('DROP TABLE presence');
        $this->addSql('DROP TABLE website');
    }
}
