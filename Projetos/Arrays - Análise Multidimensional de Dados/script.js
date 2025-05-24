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
    return CuboDeAnalise;
}());
// Instância única do cubo
var cubo = new CuboDeAnalise();
// Cadastro de vendas
var formVenda = document.getElementById("formVenda");
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
    alert("Venda adicionada!");
    formVenda.reset();
};
// Gerar gráfico
var btnGerar = document.getElementById("gerarGrafico");
btnGerar.onclick = function () {
    var dimensao = document.getElementById("dimensaoX").value;
    var dados = cubo.agruparPorDimensao(dimensao);
    desenharGrafico(dados);
};
// Desenhar gráfico no canvas
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
