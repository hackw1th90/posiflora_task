<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260408074848 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE ' . 'TABLE shops (
            id smallserial, name VARCHAR(256) NOT NULL, PRIMARY KEY (id)
        )');
        $this->addSql('CREATE ' . 'TABLE telegram_integrations (
            id smallserial,
            shop_id smallserial,
            bot_token VARCHAR(256) NOT NULL,
            chat_id VARCHAR(32) NOT NULL,
            enabled BOOLEAN NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY (id)
        )');
        $this->addSql('CREATE ' . 'UNIQUE INDEX UX_TELEGRAM_INTEGRATIONS_SHOP ON telegram_integrations (shop_id)');
        $this->addSql('CREATE ' . 'TABLE orders (
            id smallserial,
            number VARCHAR(64) NOT NULL,
            total integer,
            customer_name VARCHAR(64) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            shop_id smallserial,
            PRIMARY KEY (id)
        )');
        $this->addSql('CREATE ' . 'TABLE telegram_send_log (
            id smallserial,
            message VARCHAR(2056) NOT NULL,
            status VARCHAR(16) NOT NULL,
            error VARCHAR(512) NOT NULL,
            sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            shop_id smallserial,
            order_id smallserial,
            PRIMARY KEY (id)
        )');

        $this->addSql('CREATE ' . 'UNIQUE INDEX UX_TELEGRAM_SEND_LOG_SHOP_ORDER ON telegram_send_log (shop_id, order_id)');

        $this->addSql('CREATE ' . 'INDEX IDX_001 ON telegram_integrations (shop_id)');
        $this->addSql('CREATE ' . 'INDEX IDX_002 ON orders (shop_id)');
        $this->addSql('CREATE ' . 'INDEX IDX_003 ON telegram_send_log (shop_id)');
        $this->addSql('CREATE ' . 'INDEX IDX_004 ON telegram_send_log (order_id)');

        $this->addSql('ALTER ' . 'TABLE orders ADD CONSTRAINT FK_001 FOREIGN KEY (shop_id) REFERENCES shops (id)');
        $this->addSql('ALTER ' . 'TABLE telegram_integrations ADD CONSTRAINT FK_002 FOREIGN KEY (shop_id) REFERENCES shops (id)');
        $this->addSql('ALTER ' . 'TABLE telegram_send_log ADD CONSTRAINT FK_003 FOREIGN KEY (shop_id) REFERENCES shops (id)');
        $this->addSql('ALTER ' . 'TABLE telegram_send_log ADD CONSTRAINT FK_004 FOREIGN KEY (order_id) REFERENCES orders (id)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP ' . 'TABLE orders');
        $this->addSql('DROP ' . 'TABLE shops');
        $this->addSql('DROP ' . 'TABLE telegram_integrations');
        $this->addSql('DROP ' . 'TABLE telegram_send_log');
    }
}
