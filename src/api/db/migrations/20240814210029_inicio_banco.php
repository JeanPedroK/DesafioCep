<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InicioBanco extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {

        $this->table('cep')
            ->addColumn('cep','integer')    
            ->addColumn('lat','float')    
            ->addColumn('lng','float')
            ->addColumn('createdAt','datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex('cep')
            ->create();    

        $this->table('calculo')
            ->addColumn('cepId_origem','integer',['signed' => false])    
            ->addColumn('cepId_destino','integer',['signed' => false])    
            ->addColumn('distance','float')
            ->addColumn('createdAt','datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('cepId_origem', 'cep', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('cepId_destino', 'cep', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addIndex('cepId_origem')
            ->addIndex('cepId_destino')
            ->create();  
    }
}
