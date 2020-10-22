<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201022123057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO user (phone, roles, password, name, surname) VALUES (89895005544, "[\"ROLE_ADMIN\"]", "$argon2id$v=19$m=65536,t=4,p=1$eX4uH0JK0pgKSPWhVA7uoA$KFr3WMnqRijvyCqqnfYB8/m+KRXB5lq9iVRt/ueYwvg", "Андрей", "Смизюк")');


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM user WHERE phone = 89895005544');

    }
}
