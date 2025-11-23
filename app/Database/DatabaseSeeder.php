<?php

use App\Models\Atitude;
use App\Models\Card;
use App\Models\Conhecimento;
use App\Models\Curso;
use App\Models\Habilidade;
use App\Models\Indicador;
use App\Models\Turma;
use App\Models\Uc;

class DatabaseSeeder
{
  public function run()
  {
    $this->seedCursos();
    $this->seedUcs();
    $this->seedIndicadores();
    $this->seedConhecimentos();
    $this->seedHabilidades();
    $this->seedAtitudes();
    $this->seedTurmas();
    $this->seedCards();
    $this->seedCardRelations();
  }

  private function seedCursos()
  {
    Curso::create([
      'id' => 1,
      'nome_curso' => 'Técnico em Informática',
      'data_criacao' => '2025-11-18 13:25:52.278'
    ]);
  }

  private function seedUcs()
  {
    $ucs = [
      ['id' => 1, 'curso_id' => 1, 'numero_uc' => 1, 'sigla' => 'UC1', 'nome_completo' => 'Planejar e executar a montagem de computadores', 'data_criacao' => '2025-11-18 13:26:01.238'],
      ['id' => 2, 'curso_id' => 1, 'numero_uc' => 2, 'sigla' => 'UC2', 'nome_completo' => 'Planejar e executar a instalação de hardware e software para computadores', 'data_criacao' => '2025-11-18 13:27:51.313'],
      ['id' => 3, 'curso_id' => 1, 'numero_uc' => 3, 'sigla' => 'UC3', 'nome_completo' => 'Planejar e executar a manutenção de computadores', 'data_criacao' => '2025-11-18 13:28:55.387'],
      ['id' => 4, 'curso_id' => 1, 'numero_uc' => 4, 'sigla' => 'UC4', 'nome_completo' => 'Projeto Integrador Assistente de Suporte e Manutenção de Computadores', 'data_criacao' => '2025-11-18 13:29:45.27'],
      ['id' => 5, 'curso_id' => 1, 'numero_uc' => 5, 'sigla' => 'UC5', 'nome_completo' => 'Planejar e executar a instalação de redes locais de computadores', 'data_criacao' => '2025-11-18 13:30:23.16'],
    ];

    foreach ($ucs as $uc) {
      Uc::create($uc);
    }
  }

  private function seedIndicadores()
  {
    $indicadores = [
      ['id' => 1, 'numero_ind' => 1, 'uc_id' => 1, 'descricao' => 'Descreve as funcionalidades e aplicações da arquitetura de computadores, de acordo com as orientações técnicas do fabricante.'],
      ['id' => 2, 'numero_ind' => 2, 'uc_id' => 1, 'descricao' => 'Utiliza medidas de prevenção contra descargas eletrostáticas, de acordo com as orientações do fabricante.'],
      ['id' => 3, 'numero_ind' => 3, 'uc_id' => 1, 'descricao' => 'Testa componentes de computadores e periféricos, de acordo com as recomendações técnicas.'],
      ['id' => 4, 'numero_ind' => 4, 'uc_id' => 1, 'descricao' => 'Configura os componentes do computador conforme recomendações técnicas.'],
      ['id' => 5, 'numero_ind' => 5, 'uc_id' => 1, 'descricao' => 'Monta computadores conforme as recomendações e os procedimentos técnicos de fabricantes.'],
      ['id' => 6, 'numero_ind' => 6, 'uc_id' => 1, 'descricao' => 'Configura os parâmetros de inicialização da máquina conforme recomendações técnicas.'],
      ['id' => 7, 'numero_ind' => 7, 'uc_id' => 1, 'descricao' => 'Realiza inspeção final do equipamento conforme recomendações técnicas.'],
      ['id' => 8, 'numero_ind' => 1, 'uc_id' => 2, 'descricao' => 'Planeja e organiza os recursos de hardware conforme as necessidades da demanda e o ambiente de trabalho.'],
      ['id' => 9, 'numero_ind' => 2, 'uc_id' => 2, 'descricao' => 'Realiza a preparação física dos computadores e periféricos para instalação dos sistemas operacionais e aplicativos.'],
      ['id' => 10, 'numero_ind' => 3, 'uc_id' => 2, 'descricao' => 'Verifica compatibilidade das especificações técnicas do computador com requisitos do sistema operacional e aplicativos.'],
      ['id' => 11, 'numero_ind' => 4, 'uc_id' => 2, 'descricao' => 'Instala sistemas operacionais conforme legislação vigente de proteção à propriedade intelectual.'],
      ['id' => 12, 'numero_ind' => 5, 'uc_id' => 2, 'descricao' => 'Instala, configura e atualiza aplicativos de segurança conforme recomendações do fabricante.'],
      ['id' => 13, 'numero_ind' => 6, 'uc_id' => 2, 'descricao' => 'Instala e atualiza BIOS, firmware e drivers conforme recomendações do fabricante.'],
      ['id' => 14, 'numero_ind' => 7, 'uc_id' => 2, 'descricao' => 'Instala pacotes de atualização do sistema operacional conforme recomendações técnicas.'],
      ['id' => 15, 'numero_ind' => 8, 'uc_id' => 2, 'descricao' => 'Configura sistemas operacionais, aplicativos e periféricos conforme necessidade do cliente.'],
      ['id' => 16, 'numero_ind' => 9, 'uc_id' => 2, 'descricao' => 'Configura adaptadores de rede, redes SOHO e dispositivos móveis conforme especificações.'],
      ['id' => 17, 'numero_ind' => 10, 'uc_id' => 2, 'descricao' => 'Testa funcionamento do computador, periféricos e conectividade da rede conforme recomendações técnicas.'],
      ['id' => 18, 'numero_ind' => 1, 'uc_id' => 3, 'descricao' => 'Planeja e organiza a utilização dos recursos conforme as necessidades da demanda do cliente e o ambiente de trabalho.'],
      ['id' => 19, 'numero_ind' => 2, 'uc_id' => 3, 'descricao' => 'Realiza as etapas do processo de segurança e restauração (backup e restore) dos dados do computador conforme normas, procedimentos técnicos e legislação vigente.'],
      ['id' => 20, 'numero_ind' => 3, 'uc_id' => 3, 'descricao' => 'Verifica o funcionamento do hardware, utilizando ferramentas e técnicas para diagnóstico de falhas, de acordo com as recomendações das normas técnicas dos fabricantes.'],
      ['id' => 21, 'numero_ind' => 4, 'uc_id' => 3, 'descricao' => 'Verifica o funcionamento do software, utilizando técnicas para diagnóstico de falhas, de acordo com as recomendações dos fabricantes.'],
      ['id' => 22, 'numero_ind' => 5, 'uc_id' => 3, 'descricao' => 'Verifica e corrige problemas físicos, lógicos e de conectividade de acordo com as recomendações dos fabricantes.'],
      ['id' => 23, 'numero_ind' => 6, 'uc_id' => 3, 'descricao' => 'Instala os pacotes de atualização do sistema operacional corrigindo falhas, assegurando o desempenho do computador e a segurança de acordo com as recomendações técnicas.'],
      ['id' => 24, 'numero_ind' => 7, 'uc_id' => 3, 'descricao' => 'Testa o funcionamento do computador, dos periféricos e a conectividade da rede por meio de instrumentos e softwares específicos conforme as recomendações técnicas de cada fabricante.'],
      ['id' => 25, 'numero_ind' => 1, 'uc_id' => 4, 'descricao' => 'Integra conhecimentos das UCs 1, 2 e 3 em um projeto prático.'],
      ['id' => 26, 'numero_ind' => 2, 'uc_id' => 4, 'descricao' => 'Planeja e executa montagem, instalação e manutenção de computadores em contexto real.'],
      ['id' => 27, 'numero_ind' => 3, 'uc_id' => 4, 'descricao' => 'Apresenta soluções técnicas documentadas e alinhadas às boas práticas.'],
      ['id' => 28, 'numero_ind' => 4, 'uc_id' => 4, 'descricao' => 'Trabalha em equipe para desenvolver projeto integrador.'],
      ['id' => 29, 'numero_ind' => 5, 'uc_id' => 4, 'descricao' => 'Aplica normas técnicas e legislação vigente durante o desenvolvimento do projeto.'],
      ['id' => 30, 'numero_ind' => 1, 'uc_id' => 5, 'descricao' => 'Planeja a topologia de rede conforme as necessidades da demanda e o ambiente de trabalho.'],
      ['id' => 31, 'numero_ind' => 2, 'uc_id' => 5, 'descricao' => 'Organiza os recursos materiais e humanos necessários para a instalação da rede.'],
      ['id' => 32, 'numero_ind' => 3, 'uc_id' => 5, 'descricao' => 'Instala cabeamento estruturado de acordo com normas técnicas e recomendações dos fabricantes.'],
      ['id' => 33, 'numero_ind' => 4, 'uc_id' => 5, 'descricao' => 'Configura dispositivos de rede (switches, roteadores, access points) conforme especificações técnicas.'],
      ['id' => 34, 'numero_ind' => 5, 'uc_id' => 5, 'descricao' => 'Testa a conectividade da rede utilizando ferramentas adequadas e registra os resultados.'],
    ];

    foreach ($indicadores as $indicador) {
      Indicador::create($indicador);
    }
  }

  private function seedConhecimentos()
  {
    $conhecimentos = [
      ['id' => 1, 'numero_con' => 1, 'uc_id' => 1, 'descricao' => 'Sistema Operacional - sistemas de arquitetura aberta e fechada, estrutura de arquivos, contas de usuários.'],
      ['id' => 2, 'numero_con' => 2, 'uc_id' => 1, 'descricao' => 'Sistemas Numéricos - binário, decimal, octal, hexadecimal; unidades de armazenamento e processamento.'],
      ['id' => 3, 'numero_con' => 3, 'uc_id' => 1, 'descricao' => 'Fundamentos de arquitetura de computadores - barramentos, ULA, registradores, memória, controladores.'],
      ['id' => 4, 'numero_con' => 4, 'uc_id' => 1, 'descricao' => 'Fundamentos de eletricidade e eletrônica - tensão, corrente, potência, leis de Ohm e Kirchoff.'],
      ['id' => 5, 'numero_con' => 5, 'uc_id' => 1, 'descricao' => 'Multímetros - tipos e procedimentos de utilização.'],
      ['id' => 6, 'numero_con' => 6, 'uc_id' => 1, 'descricao' => 'Aterramento aplicado à proteção eletrostática.'],
      ['id' => 7, 'numero_con' => 7, 'uc_id' => 1, 'descricao' => 'Componentes de hardware - placa-mãe, chipsets, processadores, memórias.'],
      ['id' => 8, 'numero_con' => 8, 'uc_id' => 1, 'descricao' => 'Unidades de armazenamento - HDs, SSDs, RAID, mídias óticas.'],
      ['id' => 9, 'numero_con' => 9, 'uc_id' => 1, 'descricao' => 'Gabinetes - tipos, especificações, conectorização.'],
      ['id' => 10, 'numero_con' => 10, 'uc_id' => 1, 'descricao' => 'Sistemas de refrigeração - aircooling, water cooler, etc.'],
      ['id' => 11, 'numero_con' => 11, 'uc_id' => 1, 'descricao' => 'Periféricos - mouse, teclado, impressora, scanner, webcam, etc.'],
      ['id' => 12, 'numero_con' => 12, 'uc_id' => 1, 'descricao' => 'Ferramentas e equipamentos de medição.'],
      ['id' => 13, 'numero_con' => 13, 'uc_id' => 1, 'descricao' => 'Manuais de fabricantes para montagem.'],
      ['id' => 14, 'numero_con' => 14, 'uc_id' => 1, 'descricao' => 'Planejamento da montagem e descarte de resíduos.'],
      ['id' => 15, 'numero_con' => 15, 'uc_id' => 1, 'descricao' => 'Organização e cuidados no processo de montagem.'],
      ['id' => 16, 'numero_con' => 16, 'uc_id' => 1, 'descricao' => 'Técnicas de montagem e inspeção final.'],
      ['id' => 17, 'numero_con' => 17, 'uc_id' => 1, 'descricao' => 'Configurações de BIOS/Setup.'],
      ['id' => 18, 'numero_con' => 1, 'uc_id' => 2, 'descricao' => 'Internet - navegação e pesquisa.'],
      ['id' => 19, 'numero_con' => 2, 'uc_id' => 2, 'descricao' => 'Editor de textos - funcionalidades e aplicabilidade.'],
      ['id' => 20, 'numero_con' => 3, 'uc_id' => 2, 'descricao' => 'Manuais de fabricantes para instalação de software.'],
      ['id' => 21, 'numero_con' => 4, 'uc_id' => 2, 'descricao' => 'Sistemas Operacionais - conceitos, arquitetura, gerenciamento de processos, sistemas de arquivos.'],
      ['id' => 22, 'numero_con' => 5, 'uc_id' => 2, 'descricao' => 'Legislação de proteção à propriedade intelectual de software.'],
      ['id' => 23, 'numero_con' => 6, 'uc_id' => 2, 'descricao' => 'Princípios básicos da segurança da informação.'],
      ['id' => 24, 'numero_con' => 7, 'uc_id' => 2, 'descricao' => 'Aplicativos de apoio do sistema operacional - firmware, drivers.'],
      ['id' => 25, 'numero_con' => 8, 'uc_id' => 2, 'descricao' => 'Instalação e desinstalação de programas - aplicativos de escritório, utilitários, antivírus, players, navegadores.'],
      ['id' => 26, 'numero_con' => 1, 'uc_id' => 3, 'descricao' => 'Planilha eletrônica - funcionalidades, atalhos e aplicações.'],
      ['id' => 27, 'numero_con' => 2, 'uc_id' => 3, 'descricao' => 'Manuais de fabricantes de manutenção de hardware e software - Informações técnicas. Requisitos. Compatibilidades. Melhores práticas. Procedimentos técnicos. Sites.'],
      ['id' => 28, 'numero_con' => 3, 'uc_id' => 3, 'descricao' => 'Técnicas para análise e diagnóstico de problemas em hardware - Ferramentas de diagnósticos. Teste de componentes. Programas de detecção de erros. Dispositivos de detecção de erros.'],
      ['id' => 29, 'numero_con' => 4, 'uc_id' => 3, 'descricao' => 'Técnicas para análise e diagnóstico de problemas em software - Ferramentas de diagnósticos. Teste de compatibilidade. Teste de funcionalidade dos sistemas operacionais, aplicativos e drivers. Configurações dos sistemas operacionais e dos aplicativos.'],
      ['id' => 30, 'numero_con' => 5, 'uc_id' => 3, 'descricao' => 'Conectividade - Testes. Protocolos. Normas, padrões e especificações técnicas de fabricantes.'],
      ['id' => 31, 'numero_con' => 6, 'uc_id' => 3, 'descricao' => 'Atualizações - Hardware - computadores e periféricos. Sistemas Operacionais. Drivers. Firmware. Aplicativos utilitários.'],
      ['id' => 32, 'numero_con' => 7, 'uc_id' => 3, 'descricao' => 'Sustentabilidade - Legislação ambiental vigente. Descarte de resíduos tecnológicos.'],
      ['id' => 33, 'numero_con' => 8, 'uc_id' => 3, 'descricao' => 'Normas técnicas de segurança do trabalho - Ergonomia. Riscos visuais. Lesões de esforços repetitivos.'],
      ['id' => 34, 'numero_con' => 9, 'uc_id' => 3, 'descricao' => 'Normas técnicas de operação para reparo e manutenção - Proteção eletrostática. Manuseios. Cuidados e prevenção contra danos físicos.'],
      ['id' => 35, 'numero_con' => 10, 'uc_id' => 3, 'descricao' => 'Procedimentos de manutenção - Ferramentas de backup e restore. Ferramentas de recuperação a desastres e imagem de sistemas. Técnicas de desinstalação e instalação de sistemas operacionais, drives e aplicativos. Aterramentos. Equipamentos de medição. Desmontagem e montagem de elementos de hardware. Configuração das diretivas de segurança. Correção de falhas no sistema de arquivos. Documentação e registros.'],
      ['id' => 36, 'numero_con' => 11, 'uc_id' => 3, 'descricao' => 'Homologação do funcionamento do computador, dos periféricos e a conectividade da rede - Plano de testes. Tipos de testes: físicos e funcionais. Instrumentos e software de testes. Procedimentos de testes. Registro e documentação.'],
      ['id' => 37, 'numero_con' => 1, 'uc_id' => 4, 'descricao' => 'Integração de conceitos de montagem, instalação e manutenção de computadores.'],
      ['id' => 38, 'numero_con' => 2, 'uc_id' => 4, 'descricao' => 'Planejamento e execução de projetos técnicos.'],
      ['id' => 39, 'numero_con' => 3, 'uc_id' => 4, 'descricao' => 'Documentação técnica de projetos.'],
      ['id' => 40, 'numero_con' => 4, 'uc_id' => 4, 'descricao' => 'Gestão de tempo e recursos em projetos.'],
      ['id' => 41, 'numero_con' => 5, 'uc_id' => 4, 'descricao' => 'Normas técnicas de segurança e sustentabilidade aplicadas em projetos.'],
      ['id' => 42, 'numero_con' => 1, 'uc_id' => 5, 'descricao' => 'Topologias de rede: estrela, barramento, anel, malha.'],
      ['id' => 43, 'numero_con' => 2, 'uc_id' => 5, 'descricao' => 'Protocolos de comunicação: TCP/IP, UDP, ICMP.'],
      ['id' => 44, 'numero_con' => 3, 'uc_id' => 5, 'descricao' => 'Normas de cabeamento estruturado (TIA/EIA).'],
      ['id' => 45, 'numero_con' => 4, 'uc_id' => 5, 'descricao' => 'Dispositivos de rede: switches, roteadores, access points.'],
      ['id' => 46, 'numero_con' => 5, 'uc_id' => 5, 'descricao' => 'Ferramentas de teste de rede (testadores de cabo, softwares de diagnóstico).'],
      ['id' => 47, 'numero_con' => 6, 'uc_id' => 5, 'descricao' => 'Segurança em redes locais: controle de acesso, autenticação, criptografia.'],
      ['id' => 48, 'numero_con' => 7, 'uc_id' => 5, 'descricao' => 'Documentação técnica de instalação de redes.'],
    ];

    foreach ($conhecimentos as $conhecimento) {
      Conhecimento::create($conhecimento);
    }
  }

  private function seedHabilidades()
  {
    $habilidades = [
      ['id' => 1, 'numero_hab' => 1, 'uc_id' => 1, 'descricao' => 'Comunicar-se de maneira assertiva.'],
      ['id' => 2, 'numero_hab' => 2, 'uc_id' => 1, 'descricao' => 'Elaborar documentos técnicos.'],
      ['id' => 3, 'numero_hab' => 3, 'uc_id' => 1, 'descricao' => 'Interpretar textos técnicos.'],
      ['id' => 4, 'numero_hab' => 4, 'uc_id' => 1, 'descricao' => 'Selecionar informações necessárias ao desenvolvimento do trabalho.'],
      ['id' => 5, 'numero_hab' => 5, 'uc_id' => 1, 'descricao' => 'Organizar materiais, ferramentas e local de trabalho.'],
      ['id' => 6, 'numero_hab' => 6, 'uc_id' => 1, 'descricao' => 'Medir conflitos nas situações de trabalho.'],
      ['id' => 7, 'numero_hab' => 7, 'uc_id' => 1, 'descricao' => 'Administrar etapas do processo de instalação e recursos disponíveis.'],
      ['id' => 8, 'numero_hab' => 1, 'uc_id' => 2, 'descricao' => 'Planejar instalação de hardware e software.'],
      ['id' => 9, 'numero_hab' => 2, 'uc_id' => 2, 'descricao' => 'Configurar sistemas operacionais e aplicativos.'],
      ['id' => 10, 'numero_hab' => 3, 'uc_id' => 2, 'descricao' => 'Testar funcionamento de computadores e periféricos.'],
      ['id' => 11, 'numero_hab' => 4, 'uc_id' => 2, 'descricao' => 'Interpretar requisitos técnicos e compatibilidades.'],
      ['id' => 12, 'numero_hab' => 5, 'uc_id' => 2, 'descricao' => 'Documentar processos de instalação.'],
      ['id' => 13, 'numero_hab' => 1, 'uc_id' => 3, 'descricao' => 'Comunicar-se de maneira assertiva.'],
      ['id' => 14, 'numero_hab' => 2, 'uc_id' => 3, 'descricao' => 'Elaborar documentos técnicos'],
      ['id' => 15, 'numero_hab' => 3, 'uc_id' => 3, 'descricao' => 'Interpretar textos técnicos.'],
      ['id' => 16, 'numero_hab' => 4, 'uc_id' => 3, 'descricao' => 'Selecionar informações necessárias ao desenvolvimento do seu trabalho.'],
      ['id' => 17, 'numero_hab' => 5, 'uc_id' => 3, 'descricao' => 'Organizar materiais, ferramentas, instrumentos, documentos e local de trabalho.'],
      ['id' => 18, 'numero_hab' => 6, 'uc_id' => 3, 'descricao' => 'Administrar as etapas do processo de instalação e os recursos disponíveis.'],
      ['id' => 19, 'numero_hab' => 7, 'uc_id' => 3, 'descricao' => 'Mediar conflitos nas situações de trabalho.'],
      ['id' => 20, 'numero_hab' => 8, 'uc_id' => 3, 'descricao' => 'Analisar as etapas do processo de trabalho.'],
      ['id' => 21, 'numero_hab' => 1, 'uc_id' => 4, 'descricao' => 'Aplicar conhecimentos adquiridos em situações práticas.'],
      ['id' => 22, 'numero_hab' => 2, 'uc_id' => 4, 'descricao' => 'Trabalhar colaborativamente em equipe.'],
      ['id' => 23, 'numero_hab' => 3, 'uc_id' => 4, 'descricao' => 'Apresentar resultados de forma clara e organizada.'],
      ['id' => 24, 'numero_hab' => 4, 'uc_id' => 4, 'descricao' => 'Gerenciar etapas de um projeto técnico.'],
      ['id' => 25, 'numero_hab' => 5, 'uc_id' => 4, 'descricao' => 'Elaborar relatórios e documentação técnica do projeto.'],
      ['id' => 26, 'numero_hab' => 1, 'uc_id' => 5, 'descricao' => 'Configurar switches e roteadores.'],
      ['id' => 27, 'numero_hab' => 2, 'uc_id' => 5, 'descricao' => 'Executar testes de rede e interpretar resultados.'],
      ['id' => 28, 'numero_hab' => 3, 'uc_id' => 5, 'descricao' => 'Elaborar diagramas de rede.'],
      ['id' => 29, 'numero_hab' => 4, 'uc_id' => 5, 'descricao' => 'Documentar procedimentos de instalação.'],
    ];

    foreach ($habilidades as $habilidade) {
      Habilidade::create($habilidade);
    }
  }

  private function seedAtitudes()
  {
    $atitudes = [
      ['id' => 1, 'numero_ati' => 1, 'uc_id' => 1, 'descricao' => 'Zelo na apresentação pessoal e postura profissional.'],
      ['id' => 2, 'numero_ati' => 2, 'uc_id' => 1, 'descricao' => 'Sigilo no tratamento de dados e informações.'],
      ['id' => 3, 'numero_ati' => 3, 'uc_id' => 1, 'descricao' => 'Zelo pela segurança e integridade dos dados.'],
      ['id' => 4, 'numero_ati' => 4, 'uc_id' => 1, 'descricao' => 'Proatividade na resolução de problemas.'],
      ['id' => 5, 'numero_ati' => 5, 'uc_id' => 1, 'descricao' => 'Colaboração no trabalho em equipe.'],
      ['id' => 6, 'numero_ati' => 6, 'uc_id' => 1, 'descricao' => 'Cordialidade no trato com as pessoas.'],
      ['id' => 7, 'numero_ati' => 7, 'uc_id' => 1, 'descricao' => 'Zelo na execução de procedimentos técnicos.'],
      ['id' => 8, 'numero_ati' => 8, 'uc_id' => 1, 'descricao' => 'Responsabilidade no uso de recursos e descarte de lixo eletrônico.'],
      ['id' => 9, 'numero_ati' => 1, 'uc_id' => 2, 'descricao' => 'Responsabilidade no cumprimento da legislação de software.'],
      ['id' => 10, 'numero_ati' => 2, 'uc_id' => 2, 'descricao' => 'Zelo pela segurança da informação.'],
      ['id' => 11, 'numero_ati' => 3, 'uc_id' => 2, 'descricao' => 'Proatividade na resolução de problemas.'],
      ['id' => 12, 'numero_ati' => 4, 'uc_id' => 2, 'descricao' => 'Colaboração no trabalho em equipe.'],
      ['id' => 13, 'numero_ati' => 5, 'uc_id' => 2, 'descricao' => 'Cordialidade no atendimento ao cliente.'],
      ['id' => 14, 'numero_ati' => 1, 'uc_id' => 3, 'descricao' => 'Zelo na apresentação pessoal e postura profissional.'],
      ['id' => 15, 'numero_ati' => 2, 'uc_id' => 3, 'descricao' => 'Sigilo no tratamento de dados e informações.'],
      ['id' => 16, 'numero_ati' => 3, 'uc_id' => 3, 'descricao' => 'Zelo pela segurança e pela integridade dos dados.'],
      ['id' => 17, 'numero_ati' => 4, 'uc_id' => 3, 'descricao' => 'Proatividade na resolução de problemas.'],
      ['id' => 18, 'numero_ati' => 5, 'uc_id' => 3, 'descricao' => 'Atitude colaborativa com membros da equipe, parceiros e clientes.'],
      ['id' => 19, 'numero_ati' => 6, 'uc_id' => 3, 'descricao' => 'Cordialidade no trato com as pessoas.'],
      ['id' => 20, 'numero_ati' => 7, 'uc_id' => 3, 'descricao' => 'Zelo pela higiene, limpeza e conservação na utilização dos equipamentos, instrumentos e ferramentas.'],
      ['id' => 21, 'numero_ati' => 8, 'uc_id' => 3, 'descricao' => 'Responsabilidade no uso dos recursos organizacionais e no descarte de lixo eletrônico.'],
      ['id' => 22, 'numero_ati' => 1, 'uc_id' => 4, 'descricao' => 'Colaboração no desenvolvimento de projetos.'],
      ['id' => 23, 'numero_ati' => 2, 'uc_id' => 4, 'descricao' => 'Responsabilidade na execução das atividades.'],
      ['id' => 24, 'numero_ati' => 3, 'uc_id' => 4, 'descricao' => 'Zelo pela qualidade e segurança do trabalho.'],
      ['id' => 25, 'numero_ati' => 4, 'uc_id' => 4, 'descricao' => 'Comprometimento com prazos e resultados.'],
      ['id' => 26, 'numero_ati' => 5, 'uc_id' => 4, 'descricao' => 'Postura ética e profissional no desenvolvimento do projeto.'],
      ['id' => 27, 'numero_ati' => 6, 'uc_id' => 4, 'descricao' => 'Cuidado com o impacto ambiental das atividades realizadas.'],
      ['id' => 28, 'numero_ati' => 1, 'uc_id' => 5, 'descricao' => 'Zelo pela organização do ambiente de trabalho.'],
      ['id' => 29, 'numero_ati' => 2, 'uc_id' => 5, 'descricao' => 'Responsabilidade na segurança da rede.'],
      ['id' => 30, 'numero_ati' => 3, 'uc_id' => 5, 'descricao' => 'Colaboração no trabalho em equipe.'],
      ['id' => 31, 'numero_ati' => 4, 'uc_id' => 5, 'descricao' => 'Comprometimento com prazos e qualidade da entrega.'],
    ];

    foreach ($atitudes as $atitude) {
      Atitude::create($atitude);
    }
  }

  private function seedTurmas()
  {
    $turmas = [
      ['id' => 1, 'nome' => 'TI26'],
      ['id' => 2, 'nome' => 'TII12'],
      ['id' => 3, 'nome' => 'TI27'],
    ];

    foreach ($turmas as $turma) {
      Turma::create($turma);
    }
  }

  private function seedCards()
  {
    $cards = [
      ['id' => 1, 'titulo' => 'Planejamento inicial', 'descricao' => 'Definir escopo e metas do projeto', 'turma_id' => 1, 'uc_id' => 1, 'curso_id' => 1, 'aula_inicial' => null, 'aula_final' => null],
      ['id' => 2, 'titulo' => 'Design da interface', 'descricao' => 'Criar protótipos de telas principais', 'turma_id' => 2, 'uc_id' => 2, 'curso_id' => 1, 'aula_inicial' => null, 'aula_final' => null],
      ['id' => 3, 'titulo' => 'Configuração do banco', 'descricao' => 'Modelar entidades e relacionamentos', 'turma_id' => 2, 'uc_id' => 2, 'curso_id' => 1, 'aula_inicial' => null, 'aula_final' => null],
      ['id' => 4, 'titulo' => 'Integração com API', 'descricao' => 'Conectar frontend com backend via REST', 'turma_id' => 2, 'uc_id' => 2, 'curso_id' => 1, 'aula_inicial' => null, 'aula_final' => null],
      ['id' => 5, 'titulo' => 'Testes unitários', 'descricao' => 'Escrever testes para funções principais', 'turma_id' => 3, 'uc_id' => 3, 'curso_id' => 1, 'aula_inicial' => null, 'aula_final' => null],
      ['id' => 6, 'titulo' => 'Documentação final', 'descricao' => 'Gerar manual de uso e arquitetura', 'turma_id' => 3, 'uc_id' => 3, 'curso_id' => 1, 'aula_inicial' => null, 'aula_final' => null],
    ];

    foreach ($cards as $card) {
      Card::create($card);
    }
  }

  private function seedCardRelations()
  {
    // Exemplos de relacionamentos entre cards e indicadores/conhecimentos/habilidades/atitudes
    // Você pode adicionar mais conforme necessário

    // Card 1 - Planejamento inicial (UC1)
    $card1 = Card::find(1);
    if ($card1) {
      $card1->indicadores()->attach([1, 2]); // Indicadores 1 e 2 da UC1
      $card1->conhecimentos()->attach([1, 2, 3]); // Conhecimentos 1, 2, 3 da UC1
      $card1->habilidades()->attach([1, 2]); // Habilidades 1, 2 da UC1
      $card1->atitudes()->attach([1, 4, 5]); // Atitudes 1, 4, 5 da UC1
    }

    // Card 2 - Design da interface (UC2)
    $card2 = Card::find(2);
    if ($card2) {
      $card2->indicadores()->attach([8, 10]); // Indicadores da UC2
      $card2->conhecimentos()->attach([18, 19]); // Conhecimentos da UC2
      $card2->habilidades()->attach([8, 11]); // Habilidades da UC2
      $card2->atitudes()->attach([9, 10]); // Atitudes da UC2
    }

    // Card 3 - Configuração do banco (UC2)
    $card3 = Card::find(3);
    if ($card3) {
      $card3->indicadores()->attach([10, 11]);
      $card3->conhecimentos()->attach([20, 21]);
      $card3->habilidades()->attach([9, 12]);
      $card3->atitudes()->attach([9, 11]);
    }

    // Card 4 - Integração com API (UC2)
    $card4 = Card::find(4);
    if ($card4) {
      $card4->indicadores()->attach([11, 15, 16]);
      $card4->conhecimentos()->attach([21, 23]);
      $card4->habilidades()->attach([9, 11]);
      $card4->atitudes()->attach([10, 11]);
    }

    // Card 5 - Testes unitários (UC3)
    $card5 = Card::find(5);
    if ($card5) {
      $card5->indicadores()->attach([20, 21, 24]);
      $card5->conhecimentos()->attach([28, 29]);
      $card5->habilidades()->attach([13, 20]);
      $card5->atitudes()->attach([14, 17]);
    }

    // Card 6 - Documentação final (UC3)
    $card6 = Card::find(6);
    if ($card6) {
      $card6->indicadores()->attach([18, 24]);
      $card6->conhecimentos()->attach([26, 36]);
      $card6->habilidades()->attach([14, 16]);
      $card6->atitudes()->attach([14, 19]);
    }
  }
}
