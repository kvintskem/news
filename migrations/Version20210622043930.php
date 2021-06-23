<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20210622043930 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE article (
                            id INT AUTO_INCREMENT NOT NULL,
                            name VARCHAR(255) NOT NULL, 
                            PRIMARY KEY(id)) 
                            COLLATE `utf8_general_ci`
                            ENGINE = InnoDB;
                        ');
        $this->addSql('CREATE TABLE tags_article (tags_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_2486E5778D7B4FB4 (tags_id), INDEX IDX_2486E5777294869C (article_id), PRIMARY KEY(tags_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tags_article ADD CONSTRAINT FK_2486E5778D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_article ADD CONSTRAINT FK_2486E5777294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE articles_to_tags');
        $this->addSql('ALTER TABLE tags DROP name');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE article, tags, articles_to_tags');
        $this->addSql('CREATE TABLE articles_to_tags (article_id INT NOT NULL, tags_id INT NOT NULL, INDEX FK__article (article_id), INDEX FK__tags (tags_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE articles_to_tags ADD CONSTRAINT FK__article FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_to_tags ADD CONSTRAINT FK__tags FOREIGN KEY (tags_id) REFERENCES tags (id)');
        $this->addSql('DROP TABLE tags_article');
        $this->addSql('ALTER TABLE tags ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');

    }
}
