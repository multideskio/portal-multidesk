<?php

namespace App\Services;

use App\Gateway\Mp\MPProcessor;
use App\Models\TransacoesModel;
use App\Models\PedidoModel;

class PagamentoService
{
   public function processarPixMercadoPago(array $client, array $carrinho, int $idPedido, array $credenciais): bool
   {
      $empresaId = $credenciais['empresa_id'];

      $pedido = [
         'valor' => $carrinho['total'],
         'descricao' => $carrinho['evento_titulo'],
         //'notification_url' => base_url('webhook/mercadopago'),
         'referencia' => 'pedido_' . $idPedido,
      ];

      $nome = strtok($client['nome'], ' ') ?: '';
      $sobrenome = trim(strstr($client['nome'], ' ', false)) ?: '';

      $cliente = [
         'cpf' => preg_replace('/\D/', '', $client['cpf']),
         'nome' => $nome,
         'sobrenome' => $sobrenome,
         'email' => $client['email'],
         'cep' => preg_replace('/\D/', '', $client['cep']),
         'logradouro' => $client['rua'],
         'numero' => $client['numero'],
         'bairro' => $client['bairro'],
         'cidade' => $client['cidade'],
         'uf' => strtoupper($client['uf']),
      ];

      $mp = new MPProcessor();
      $resposta = $mp->processar('pix', $empresaId, $pedido, $cliente);

      // Salva transação
      $transacaoModel = new TransacoesModel();
      $transacaoModel->salvarTransacao($idPedido, $empresaId, $resposta);

      // Atualiza pedido
      $pedidoModel = new PedidoModel();
      $pedidoModel->atualizarStatus($idPedido, 'aguardando_pagamento');

      return true;
   }
}
