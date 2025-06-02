// Referências dos elementos HTML
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
    CuboDeAnalise.prototype.agruparPorDuasDimensoes = function (dim1, dim2) {
        var resultado = {};
        for (var i = 0; i < this.vendas.length; i++) {
            var v = this.vendas[i];
            var chave1 = v[dim1].toString();
            var chave2 = v[dim2].toString();
            if (!resultado[chave1]) {
                resultado[chave1] = {};
            }
            if (!resultado[chave1][chave2]) {
                resultado[chave1][chave2] = 0;
            }
            resultado[chave1][chave2] += v.quantidade;
        }
        return resultado;
    };
    return CuboDeAnalise;
}());
// Instância única
var cubo = new CuboDeAnalise();
// Formulário de vendas
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
    alert("Venda cadastrada com sucesso!");
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
// Desenho do gráfico
function desenharGraficoDuasDimensoes(dados, dim1, dim2) {
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    var chaves1 = new Array(0);
    var chaves2 = new Array(0);
    // Pegar chaves1
    for (var k1 in dados) {
        var novoIdx = chaves1.length;
        var novoArr = new Array(novoIdx + 1);
        for (var i = 0; i < novoIdx; i++) {
            novoArr[i] = chaves1[i];
        }
        novoArr[novoIdx] = k1;
        chaves1.length = 0;
        for (var i = 0; i < novoArr.length; i++)
            chaves1[i] = novoArr[i];
        // Pegar chaves2 únicas
        for (var k2 in dados[k1]) {
            var existe = false;
            for (var j = 0; j < chaves2.length; j++) {
                if (chaves2[j] === k2) {
                    existe = true;
                    break;
                }
            }
            if (!existe) {
                var novoCh2 = new Array(chaves2.length + 1);
                for (var j = 0; j < chaves2.length; j++) {
                    novoCh2[j] = chaves2[j];
                }
                novoCh2[chaves2.length] = k2;
                chaves2.length = 0;
                for (var j = 0; j < novoCh2.length; j++)
                    chaves2[j] = novoCh2[j];
            }
        }
    }
    var larguraBarra = 20;
    var espacamento = 10;
    var grupoEspaco = 40;
    var alturaMax = 300;
    var maxQuantidade = 0;
    for (var i = 0; i < chaves1.length; i++) {
        for (var j = 0; j < chaves2.length; j++) {
            var quantidade = dados[chaves1[i]][chaves2[j]] || 0;
            if (quantidade > maxQuantidade)
                maxQuantidade = quantidade;
        }
    }
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
        // Nome do grupo
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        var xGrupo = i * (chaves2.length * (larguraBarra + espacamento) + grupoEspaco) +
            (chaves2.length * (larguraBarra + espacamento)) / 2 + 50 - espacamento;
        ctx.fillText(chaves1[i], xGrupo, canvas.height - 30);
    }
    // Nome da dimensão Y
    ctx.save();
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.font = "16px Arial";
    ctx.translate(20, canvas.height / 2);
    ctx.rotate(-Math.PI / 2);
    ctx.fillText(dim2, 0, 0);
    ctx.restore();
    // Nome da dimensão X
    ctx.save();
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.font = "16px Arial";
    ctx.fillText(dim1, canvas.width / 2, canvas.height - 8);
    ctx.restore();
    // Legenda
    for (var j = 0; j < chaves2.length; j++) {
        ctx.fillStyle = ["#4682b4", "#ff7f50", "#90ee90", "#ffd700", "#d2691e"][j % 5];
        ctx.fillRect(60 + j * 100, 10, 15, 15);
        ctx.fillStyle = "#1E1E1E";
        ctx.textAlign = "left";
        ctx.fillText(chaves2[j], 80 + j * 100, 22);
    }
    ctx.textAlign = "left";
}
