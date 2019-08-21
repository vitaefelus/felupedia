<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190528083934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862EE45BDBF');
        $this->addSql('CREATE TABLE paragraphs (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, picture_id INT DEFAULT NULL, subheading VARCHAR(255) NOT NULL, text_content LONGTEXT NOT NULL, INDEX IDX_B8CF1CE77294869C (article_id), UNIQUE INDEX UNIQ_B8CF1CE7EE45BDBF (picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures (id INT AUTO_INCREMENT NOT NULL, src VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraphs ADD CONSTRAINT FK_B8CF1CE77294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE paragraphs ADD CONSTRAINT FK_B8CF1CE7EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
        $this->addSql('DROP TABLE paragraph');
        $this->addSql('DROP TABLE picture');
        $this->addSql('ALTER TABLE comments ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE articles ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paragraphs DROP FOREIGN KEY FK_B8CF1CE7EE45BDBF');
        $this->addSql('CREATE TABLE paragraph (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, picture_id INT DEFAULT NULL, subheading VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text_content LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_7DD398627294869C (article_id), UNIQUE INDEX UNIQ_7DD39862EE45BDBF (picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, src VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD398627294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('DROP TABLE paragraphs');
        $this->addSql('DROP TABLE pictures');
        $this->addSql('ALTER TABLE articles DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE comments DROP created_at, DROP updated_at');
    }
}
