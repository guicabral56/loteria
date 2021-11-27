<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Core\Domain\Jogo;
use ReflectionClass;

class JogoTest extends TestCase
{
    /**
     * setUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testDezenasErro()
    {
        $this->expectException('InvalidArgumentException');
        $class = new Jogo(1, 1);
    }

    public function testDezenasSuccesso()
    {
        $class = new Jogo(8, 1);
        $this->assertIsNumeric($class->dezenas);
    }

    public function testGerarNumeroDeDezenas()
    {
        $dezenas = 8;
        $object = new Jogo($dezenas, 1);
        $class = new ReflectionClass($object);
        $method = $class->getMethod('gerarDezenas');
        $method->setAccessible(true);
        $jogo = $method->invokeArgs($object, []);

        $this->assertEquals($dezenas, count($jogo));
    }

    public function testGerarDezenasSemRepetir()
    {
        $dezenas = 8;
        $object = new Jogo($dezenas, 1);
        $class = new ReflectionClass($object);
        $method = $class->getMethod('gerarDezenas');
        $method->setAccessible(true);
        $jogo = $method->invokeArgs($object, []);

        $uniques = array_unique($jogo);
        $this->assertEquals($dezenas, count($uniques), implode(" ", $uniques));
    }

    public function testGerarJogos()
    {
        //com 2 jogos
        $Jogo = new Jogo(6, 2);

        $Jogo->gerarJogos();

        $this->assertIsArray($Jogo->jogos);
        $this->assertEquals(2, count($Jogo->jogos));

        //com 3 jogos
        $Jogo = new Jogo(6, 3);

        $Jogo->gerarJogos();

        $this->assertIsArray($Jogo->jogos);
        $this->assertEquals(3, count($Jogo->jogos));
    }

    public function testGerarResultado()
    {
        $Jogo = new Jogo(6, 1);
        $Jogo->sortear();
        $this->assertIsArray($Jogo->resultado);
        $this->assertEquals(6, count($Jogo->resultado));

        //testa se houve algum numero repetido que será removido do array
        $unicos = array_unique($Jogo->resultado);
        $this->assertEquals(6, count($unicos));
    }

    public function testFuncaoConferirJogo()
    {
        $dezenas = 8;
        $object = new Jogo($dezenas, 1);
        $object->gerarJogos();
        $object->sortear();
        $class = new ReflectionClass($object);
        $method = $class->getMethod('conferirJogo');
        $method->setAccessible(true);

        //pega primeira dezena do Resultado
        $primeira = $object->resultado[0];

        $dezenasConferidas = $method->invokeArgs($object, [ [$primeira] ]);

        $this->assertEquals(1, count($dezenasConferidas));
    }

    public function testFuncaoEscreverResultado()
    {
        $dezenas = 8;
        $object = new Jogo($dezenas, 6);
        $object->gerarJogos();
        $object->sortear();

        $table = $object->escreverResultado();
        $this->assertIsString($table);

        fwrite(STDOUT, $table);
    }
}
