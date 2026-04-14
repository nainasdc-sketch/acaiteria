<section class="view_05" id="formulario">
        <div class="container">
            <div class="row">
                <h2 class="title">SEJA UM FRANQUEADO</h2>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="table_view">

                        <h3 class="subtitle">
                            Franquias
                        </h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Loja</th>
                                    <th>Endereço</th>
                                    <th>Atendimento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Amor de Açaí (Recanto Das Emas)</td>
                                    <td>QD 103 CJ 12 LT 03 (Atrás das Casas Bahia)</td>
                                    <td>SEG A SAB de 11:30 às 19:00<br>DOM 12:00 às 17:00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form">
                        <h3 class="subtitle">Entre em Contato</h3>
                        <form id="solicitarFranquiaForm">
                            <label for="nome">Nome:</label>
                            <input type="text" id="nome" name="nome" required>
                            
                            <label for="sobrenome">Sobrenome:</label>
                            <input type="text" id="sobrenome" name="sobrenome" required>
                            
                            <label for="email">E-mail:</label>
                            <input type="mail" id="email" name="email" required>
                            
                            <label for="telefone">Telefone:</label>
                            <input type="tel" id="telefone" name="fone" oninput="formatarTelefone(this)" placeholder="(99) 99999-9999" required>
                            
                            <label for="cidade">Estado:</label>
                            <select name="estado" id="estado">
                                <option value="">Selecione o Estado...</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>

                            <label for="cidade">Cidade:</label>
                            <input type="text" id="cidade" name="cidade" required>
                            <input type="hidden" id="action" name="action" value="cadastrar" required>
                            
                            <button type="submit">Enviar Formulário</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
