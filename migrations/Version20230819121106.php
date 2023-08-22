<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819121106 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fruit (id INT NOT NULL, nutrition_id INT NOT NULL, name VARCHAR(255) NOT NULL, family VARCHAR(255) NOT NULL, order_name VARCHAR(255) NOT NULL, genus VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A00BD297B5D724CD (nutrition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nutrition (id INT AUTO_INCREMENT NOT NULL, calories INT NOT NULL, fat INT NOT NULL, sugar INT NOT NULL, carbohydrates INT NOT NULL, protein INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fruit ADD CONSTRAINT FK_A00BD297B5D724CD FOREIGN KEY (nutrition_id) REFERENCES nutrition (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fruit DROP FOREIGN KEY FK_A00BD297B5D724CD');
        $this->addSql('DROP TABLE fruit');
        $this->addSql('DROP TABLE nutrition');
    }
}
