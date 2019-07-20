<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190720120814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create contacts table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('create table contacts (
            id int not null primary key auto_increment,
            name varchar(255) not null,
            email varchar(255),
            city varchar(255),
            country varchar(255),
            job varchar(255)
        ) engine innodb charset utf8mb4');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('drop table contacts');
    }
}
