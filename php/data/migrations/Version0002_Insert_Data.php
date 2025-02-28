<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version0002_Insert_Data extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавляем данные в талицы user, service';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO \"user\" (email, password)
            VALUES ('test@example.com', '$2y$13\$fkD4Q1iJtW8nmJcCF4H3fuBIIiiMHAXqS4Mb0WxbDJLQ6ZKBXiTSm')
        ");

        $this->addSql("
            INSERT INTO service (name, price)
            VALUES 
                ('Оценка недвижимости', 100),
                ('Оценка автомобиля', 200),
                ('Оценка бизнеса', 300);
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
