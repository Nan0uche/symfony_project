<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240221100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users, articles, stock and cart tables';
    }

    public function up(Schema $schema): void
    {
        // Users table
        $this->addSql('CREATE TABLE user (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            roles CLOB NOT NULL --(DC2Type:json)
            , 
            password VARCHAR(255) NOT NULL, 
            username VARCHAR(255) NOT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');

        // Articles table
        $this->addSql('CREATE TABLE article (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            author_id INTEGER NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            description CLOB NOT NULL, 
            price DOUBLE PRECISION NOT NULL, 
            image_url VARCHAR(255) DEFAULT NULL, 
            publish_date DATETIME NOT NULL,
            CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        )');
        $this->addSql('CREATE INDEX IDX_23A0E66F675F31B ON article (author_id)');

        // Stock table
        $this->addSql('CREATE TABLE stock (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            article_id INTEGER NOT NULL, 
            quantity INTEGER NOT NULL,
            CONSTRAINT FK_4B3656607294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B3656607294869C ON stock (article_id)');

        // Cart table
        $this->addSql('CREATE TABLE cart (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            user_id INTEGER NOT NULL, 
            article_id INTEGER NOT NULL, 
            quantity INTEGER NOT NULL,
            CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE,
            CONSTRAINT FK_BA388B77294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        )');
        $this->addSql('CREATE INDEX IDX_BA388B7A76ED395 ON cart (user_id)');
        $this->addSql('CREATE INDEX IDX_BA388B77294869C ON cart (article_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE user');
    }
} 