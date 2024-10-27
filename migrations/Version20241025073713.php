<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025073713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Produce table (Super class), then Fruit and Vegetable tables using the joined strategy to hold any specific fields we will add to them later';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fruit (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produce (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, weight INT NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vegetable (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fruit ADD CONSTRAINT FK_A00BD297BF396750 FOREIGN KEY (id) REFERENCES produce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vegetable ADD CONSTRAINT FK_DB9894F7BF396750 FOREIGN KEY (id) REFERENCES produce (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit DROP FOREIGN KEY FK_A00BD297BF396750');
        $this->addSql('ALTER TABLE vegetable DROP FOREIGN KEY FK_DB9894F7BF396750');
        $this->addSql('DROP TABLE fruit');
        $this->addSql('DROP TABLE produce');
        $this->addSql('DROP TABLE vegetable');
    }
}
