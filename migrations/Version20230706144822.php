<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706144822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acquisition (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nft_id INT NOT NULL, value DOUBLE PRECISION DEFAULT NULL, is_sold TINYINT(1) NOT NULL, INDEX IDX_2FEB9033A76ED395 (user_id), INDEX IDX_2FEB9033E813668D (nft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours_nft (id INT AUTO_INCREMENT NOT NULL, nft_id INT NOT NULL, created_at DATETIME NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_790C525BE813668D (nft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_genre (genre_source INT NOT NULL, genre_target INT NOT NULL, INDEX IDX_3E562C3DB4394F53 (genre_source), INDEX IDX_3E562C3DADDC1FDC (genre_target), PRIMARY KEY(genre_source, genre_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, genre_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4B98C214296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, date_drop DATETIME NOT NULL, annee_album INT NOT NULL, identification_token VARCHAR(255) NOT NULL, INDEX IDX_D9C7463C7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acquisition ADD CONSTRAINT FK_2FEB9033A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE acquisition ADD CONSTRAINT FK_2FEB9033E813668D FOREIGN KEY (nft_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE cours_nft ADD CONSTRAINT FK_790C525BE813668D FOREIGN KEY (nft_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE genre_genre ADD CONSTRAINT FK_3E562C3DB4394F53 FOREIGN KEY (genre_source) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_genre ADD CONSTRAINT FK_3E562C3DADDC1FDC FOREIGN KEY (genre_target) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C214296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acquisition DROP FOREIGN KEY FK_2FEB9033A76ED395');
        $this->addSql('ALTER TABLE acquisition DROP FOREIGN KEY FK_2FEB9033E813668D');
        $this->addSql('ALTER TABLE cours_nft DROP FOREIGN KEY FK_790C525BE813668D');
        $this->addSql('ALTER TABLE genre_genre DROP FOREIGN KEY FK_3E562C3DB4394F53');
        $this->addSql('ALTER TABLE genre_genre DROP FOREIGN KEY FK_3E562C3DADDC1FDC');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C214296D31F');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C7A45358C');
        $this->addSql('DROP TABLE acquisition');
        $this->addSql('DROP TABLE cours_nft');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_genre');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE nft');
    }
}
