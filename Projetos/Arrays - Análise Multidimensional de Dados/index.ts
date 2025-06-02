// Referências dos elementos HTML
const AddVendaBtn = document.getElementById("addVenda-btn") as HTMLButtonElement;
const analiseSection = document.getElementById("analise-container") as HTMLElement;
const formVendaContainer = document.getElementById("form-venda-container") as HTMLElement;
const mainHeader = document.querySelector("main > header") as HTMLElement;

AddVendaBtn.onclick = function (): void {
    analiseSection.style.display = "none";
    mainHeader.style.display = "none";
    formVendaContainer.style.display = "block";
};

// Classe Venda
class Venda {
    ano: number;
    mes: number;
    filial: string;
    categoria: string;
    autor: string;
    quantidade: number;

    constructor(ano: number, mes: number, filial: string, categoria: string, autor: string, quantidade: number) {
        this.ano = ano;
        this.mes = mes;
        this.filial = filial;
        this.categoria = categoria;
        this.autor = autor;
        this.quantidade = quantidade;
    }
}

// Classe CuboDeAnalise
class CuboDeAnalise {
    vendas: Venda[];

    constructor() {
        this.vendas = new Array(0);
    }

    adicionarVenda(venda: Venda): void {
        const novoArray = new Array(this.vendas.length + 1);
        for (let i = 0; i < this.vendas.length; i++) {
            novoArray[i] = this.vendas[i];
        }
        novoArray[this.vendas.length] = venda;
        this.vendas = novoArray;
    }

    agruparPorDuasDimensoes(dim1: keyof Venda, dim2: keyof Venda): { [k1: string]: { [k2: string]: number } } {
        const resultado: { [k1: string]: { [k2: string]: number } } = {};
        for (let i = 0; i < this.vendas.length; i++) {
            const v = this.vendas[i];
            const chave1 = v[dim1].toString();
            const chave2 = v[dim2].toString();

            if (!resultado[chave1]) {
                resultado[chave1] = {};
            }

            if (!resultado[chave1][chave2]) {
                resultado[chave1][chave2] = 0;
            }

            resultado[chave1][chave2] += v.quantidade;
        }
        return resultado;
    }
}

// Instância única
const cubo = new CuboDeAnalise();

// Formulário de vendas
const formVenda = document.getElementById("formVenda") as HTMLFormElement;

formVenda.onsubmit = function (e: Event): void {
    e.preventDefault();

    const ano = parseInt((document.getElementById("ano") as HTMLInputElement).value);
    const mes = parseInt((document.getElementById("mes") as HTMLInputElement).value);
    const filial = (document.getElementById("filial") as HTMLInputElement).value;
    const categoria = (document.getElementById("categoria") as HTMLInputElement).value;
    const autor = (document.getElementById("autor") as HTMLInputElement).value;
    const quantidade = parseInt((document.getElementById("quantidade") as HTMLInputElement).value);

    const venda = new Venda(ano, mes, filial, categoria, autor, quantidade);
    cubo.adicionarVenda(venda);

    alert ("Venda cadastrada com sucesso!");
    formVenda.reset();

    analiseSection.style.display = "block";
    mainHeader.style.display = "block";
    formVendaContainer.style.display = "none";
};

// Gerar gráfico
const btnGerar = document.getElementById("gerarGrafico") as HTMLButtonElement;
btnGerar.onclick = function (): void {
    const dimensao1 = (document.getElementById("dimensaoX") as HTMLSelectElement).value as keyof Venda;
    const dimensao2 = (document.getElementById("dimensaoY") as HTMLSelectElement).value as keyof Venda;

    if (dimensao1 === dimensao2) {
        alert("Selecione dimensões diferentes para X e Y.");
        return;
    }

    const dados = cubo.agruparPorDuasDimensoes(dimensao1, dimensao2);
    desenharGraficoDuasDimensoes(dados, dimensao1, dimensao2);
};

// Desenho do gráfico
function desenharGraficoDuasDimensoes(
    dados: { [k1: string]: { [k2: string]: number } },
    dim1: string,
    dim2: string
): void {
    const canvas = document.getElementById("canvas") as HTMLCanvasElement;
    const ctx = canvas.getContext("2d")!;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const chaves1: string[] = new Array(0);
    const chaves2: string[] = new Array(0);

    // Pegar chaves1
    for (const k1 in dados) {
        const novoIdx = chaves1.length;
        const novoArr = new Array(novoIdx + 1);
        for (let i = 0; i < novoIdx; i++) {
            novoArr[i] = chaves1[i];
        }
        novoArr[novoIdx] = k1;
        chaves1.length = 0;
        for (let i = 0; i < novoArr.length; i++) chaves1[i] = novoArr[i];

        // Pegar chaves2 únicas
        for (const k2 in dados[k1]) {
            let existe = false;
            for (let j = 0; j < chaves2.length; j++) {
                if (chaves2[j] === k2) {
                    existe = true;
                    break;
                }
            }
            if (!existe) {
                const novoCh2 = new Array(chaves2.length + 1);
                for (let j = 0; j < chaves2.length; j++) {
                    novoCh2[j] = chaves2[j];
                }
                novoCh2[chaves2.length] = k2;
                chaves2.length = 0;
                for (let j = 0; j < novoCh2.length; j++) chaves2[j] = novoCh2[j];
            }
        }
    }

    const larguraBarra = 20;
    const espacamento = 10;
    const grupoEspaco = 40;
    const alturaMax = 300;
    let maxQuantidade = 0;

    for (let i = 0; i < chaves1.length; i++) {
        for (let j = 0; j < chaves2.length; j++) {
            const quantidade = dados[chaves1[i]][chaves2[j]] || 0;
            if (quantidade > maxQuantidade) maxQuantidade = quantidade;
        }
    }

    for (let i = 0; i < chaves1.length; i++) {
        for (let j = 0; j < chaves2.length; j++) {
            const quantidade = dados[chaves1[i]][chaves2[j]] || 0;
            const altura = maxQuantidade > 0 ? (quantidade / maxQuantidade) * alturaMax : 0;
            const x = i * (chaves2.length * (larguraBarra + espacamento) + grupoEspaco) + j * (larguraBarra + espacamento) + 50;
            const y = canvas.height - altura - 30;

            ctx.fillStyle = ["#4682b4", "#ff7f50", "#90ee90", "#ffd700", "#d2691e"][j % 5];
            ctx.fillRect(x, y, larguraBarra, altura);
            ctx.fillStyle = "black";
            ctx.fillText(quantidade.toString(), x, y - 5);
        }

        // Nome do grupo
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        const xGrupo = i * (chaves2.length * (larguraBarra + espacamento) + grupoEspaco) +
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
    for (let j = 0; j < chaves2.length; j++) {
        ctx.fillStyle = ["#4682b4", "#ff7f50", "#90ee90", "#ffd700", "#d2691e"][j % 5];
        ctx.fillRect(60 + j * 100, 10, 15, 15);
        ctx.fillStyle = "#1E1E1E";
        ctx.textAlign = "left";
        ctx.fillText(chaves2[j], 80 + j * 100, 22);
    }

    ctx.textAlign = "left";
}
