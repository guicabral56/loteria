<?php

namespace Core\Domain;

class Jogo
{
    private $resultado;

    private $dezenas;

    private $totalDeJogos;

    private $jogos;

    public function __construct($dezenas, $totalDeJogos)
    {
        $this->__set('dezenas', $dezenas);
        $this->__set('totalDeJogos', $totalDeJogos);
    }

    /**
     * __set
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value): void
    {
        if ($name == 'dezenas') {
            switch ($value) {
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                    $this->$name = $value;
                    break;

                default:
                    throw new \InvalidArgumentException("Valor inválido para dezenas", 1);

                    break;
            }
        }
        $this->$name = $value;
    }

    /**
     * __get
     *
     * @param  string $name
     * @return void
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * gerarDezenas
     *
     * @return array
     */
    private function gerarDezenas()
    {
        $dezenas = [];

        for ($i = 0; $i < $this->dezenas; $i++) {
            do {
                $dezena = rand(0, 60);
            } while (in_array($dezena, $dezenas));

            $dezenas[] = $dezena;
        }

        sort($dezenas);
        return $dezenas;
    }

    public function gerarJogos()
    {
        for ($i = 0; $i < $this->totalDeJogos; $i++) {
            $this->jogos[$i] = $this->gerarDezenas();
        }
    }

    /**
     * sortear
     * Sorteia 6 dezenas aleatórias
     * @return void
     */
    public function sortear()
    {
        $resultado = [];

        for ($i = 0; $i < 6; $i++) {
            do {
                $dezena = rand(0, 60);
            } while (in_array($dezena, $resultado));

            $resultado[] = $dezena;
        }

        sort($resultado);
        $this->resultado = $resultado;
    }

    /**
     * conferirJogo
     * Confere jogo e retorna um array contendo as dezenas acertadas
     * @param  array $jogo
     * @return array
     */
    private function conferirJogo(array $jogo): array
    {
        return array_intersect($this->resultado, $jogo);
    }

    public function escreverResultado()
    {
        if (is_null($this->resultado)) {
            throw new \Exception("Resultado não sorteado", 1);
        }

        $output = "<table >
            <thead>
                <tr>
                    <th>Jogo</th>
                    <th>Total de Dezenas Sorteadas </th>
                </th>
            </thead>";

        foreach ($this->jogos as $jogo) {
            $dezenasSorteadas = $this->conferirJogo($jogo);
            $output .= "<tr>
                    <td> " . implode(" ", $jogo) . "</td>
                    <td> " . count($dezenasSorteadas) . "</td>
                </tr>";
        }

        $output .= "</table>";

        return $output;
    }
}
