var AddVendaBtn = document.getElementById("addVenda-btn");
var analiseSection = document.getElementById("analise-container");
var formVendaContainer = document.getElementById("form-venda-container");
var mainHeader = document.querySelector("main > header");
AddVendaBtn.onclick = function () {
    analiseSection.style.display = "none";
    mainHeader.style.display = "none";
    formVendaContainer.style.display = "block";
};
// Classe Venda
var Venda = /** @class */ (function () {
    function Venda(ano, mes, filial, categoria, autor, quantidade) {
        this.ano = ano;
        this.mes = mes;
        this.filial = filial;
        this.categoria = categoria;
        this.autor = autor;
        this.quantidade = quantidade;
    }
    return Venda;
}());
// Classe CuboDeAnalise
var CuboDeAnalise = /** @class */ (function () {
    function CuboDeAnalise() {
        this.vendas = new Array(0);
    }
    CuboDeAnalise.prototype.adicionarVenda = function (venda) {
        var novoArray = new Array(this.vendas.length + 1);
        for (var i = 0; i < this.vendas.length; i++) {
            novoArray[i] = this.vendas[i];
        }
        novoArray[this.vendas.length] = venda;
        this.vendas = novoArray;
    };
    // Agrupa as vendas por uma dimensão e soma as quantidades
    CuboDeAnalise.prototype.agruparPorDimensao = function (dim) {
        var resultado = new Array(0);
        for (var i = 0; i < this.vendas.length; i++) {
            var v = this.vendas[i];
            var chave = "";
            switch (dim) {
                case "filial":
                    chave = v.filial;
                    break;
                case "categoria":
                    chave = v.categoria;
                    break;
                case "autor":
                    chave = v.autor;
                    break;
                case "mes":
                    chave = v.mes.toString();
                    break;
                case "ano":
                    chave = v.ano.toString();
                    break;
                default:
                    chave = "";
                    break;
            }
            var encontrado = false;
            for (var j = 0; j < resultado.length; j++) {
                if (resultado[j].chave === chave) {
                    resultado[j].quantidade += v.quantidade;
                    encontrado = true;
                    break;
                }
            }
            if (!encontrado) {
                // adicionar novo agrupamento
                var novoArray = new Array(resultado.length + 1);
                for (var j = 0; j < resultado.length; j++) {
                    novoArray[j] = resultado[j];
                }
                novoArray[resultado.length] = { chave: chave, quantidade: v.quantidade };
                resultado.length = novoArray.length;
                for (var j = 0; j < novoArray.length; j++) {
                    resultado[j] = novoArray[j];
                }
            }
        }
        return resultado;
    };
    // Agrupa as vendas por duas dimensões e soma as quantidades
    CuboDeAnalise.prototype.agruparPorDuasDimensoes = function (dim1, dim2) {
        var resultado = {};
        for (var i = 0; i < this.vendas.length; i++) {
            var v = this.vendas[i];
            var chave1 = v[dim1].toString();
            var chave2 = v[dim2].toString();
            if (!resultado[chave1])
                resultado[chave1] = {};
            if (!resultado[chave1][chave2])
                resultado[chave1][chave2] = 0;
            resultado[chave1][chave2] += v.quantidade;
        }
        return resultado;
    };
    return CuboDeAnalise;
}());
// Instância única do cubo
var cubo = new CuboDeAnalise();
// Cadastro de vendas
var formVenda = document.getElementById("formVenda");
var mensagemVenda = document.getElementById("mensagemVenda");
formVenda.onsubmit = function (e) {
    e.preventDefault();
    var ano = parseInt(document.getElementById("ano").value);
    var mes = parseInt(document.getElementById("mes").value);
    var filial = document.getElementById("filial").value;
    var categoria = document.getElementById("categoria").value;
    var autor = document.getElementById("autor").value;
    var quantidade = parseInt(document.getElementById("quantidade").value);
    var venda = new Venda(ano, mes, filial, categoria, autor, quantidade);
    cubo.adicionarVenda(venda);
    mensagemVenda.textContent = "Venda cadastrada com sucesso!";
    formVenda.reset();
    analiseSection.style.display = "block";
    mainHeader.style.display = "block";
    formVendaContainer.style.display = "none";
};
// Gerar gráfico
var btnGerar = document.getElementById("gerarGrafico");
btnGerar.onclick = function () {
    var dimensao1 = document.getElementById("dimensaoX").value;
    var dimensao2 = document.getElementById("dimensaoY").value;
    if (dimensao1 === dimensao2) {
        alert("Selecione dimensões diferentes para X e Y.");
        return;
    }
    var dados = cubo.agruparPorDuasDimensoes(dimensao1, dimensao2);
    desenharGraficoDuasDimensoes(dados, dimensao1, dimensao2);
};
// Gráfico de uma dimensão
function desenharGrafico(dados) {
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    var larguraBarra = 40;
    var espacamento = 20;
    var alturaMax = 300;
    var maxQuantidade = getMaxQuantidade(dados);
    for (var i = 0; i < dados.length; i++) {
        var altura = (dados[i].quantidade / maxQuantidade) * alturaMax;
        var x = i * (larguraBarra + espacamento) + 50;
        var y = canvas.height - altura - 30;
        ctx.fillStyle = "steelblue";
        ctx.fillRect(x, y, larguraBarra, altura);
        // Texto chave
        ctx.fillStyle = "black";
        ctx.fillText(dados[i].chave, x, canvas.height - 10);
        // Quantidade
        ctx.fillText(dados[i].quantidade.toString(), x, y - 5);
    }
}
function getMaxQuantidade(dados) {
    var max = 0;
    for (var i = 0; i < dados.length; i++) {
        if (dados[i].quantidade > max) {
            max = dados[i].quantidade;
        }
    }
    return max;
}
// Gráfico de duas dimensões (barras agrupadas)
function desenharGraficoDuasDimensoes(dados, dim1, dim2) {
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    var chaves1 = Object.keys(dados);
    var chaves2 = [];
    chaves1.forEach(function (k1) {
        Object.keys(dados[k1]).forEach(function (k2) {
            if (!chaves2.includes(k2))
                chaves2.push(k2);
        });
    });
    var larguraBarra = 20;
    var espacamento = 10;
    var grupoEspaco = 40;
    var alturaMax = 300;
    // Descobrir o máximo
    var maxQuantidade = 0;
    chaves1.forEach(function (k1) {
        chaves2.forEach(function (k2) {
            if (dados[k1][k2] && dados[k1][k2] > maxQuantidade) {
                maxQuantidade = dados[k1][k2];
            }
        });
    });
    for (var i = 0; i < chaves1.length; i++) {
        for (var j = 0; j < chaves2.length; j++) {
            var quantidade = dados[chaves1[i]][chaves2[j]] || 0;
            var altura = maxQuantidade > 0 ? (quantidade / maxQuantidade) * alturaMax : 0;
            var x = i * (chaves2.length * (larguraBarra + espacamento) + grupoEspaco) + j * (larguraBarra + espacamento) + 50;
            var y = canvas.height - altura - 30;
            ctx.fillStyle = ["#4682b4", "#ff7f50", "#90ee90", "#ffd700", "#d2691e"][j % 5];
            ctx.fillRect(x, y, larguraBarra, altura);
            ctx.fillStyle = "black";
            ctx.fillText(quantidade.toString(), x, y - 5);
        }
        // Nome do grupo principal
        ctx.fillText(chaves1[i], i * (chaves2.length * (larguraBarra + espacamento) + grupoEspaco) + 50, canvas.height - 10);
    }
}
