const AddVendaBtn = document.getElementById("addVenda-btn") as HTMLButtonElement;
const analiseSection = document.getElementById("analise-container") as HTMLElement;
const formVendaContainer = document.getElementById("form-venda-container") as HTMLElement;
const mainHeader = document.querySelector("main > header") as HTMLElement;

AddVendaBtn.onclick = () => {
  analiseSection.style.display = "none";
  mainHeader.style.display = "none";
  formVendaContainer.style.display = "block";
}

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

  // Agrupa as vendas por uma dimensão e soma as quantidades
  agruparPorDimensao(dim: string): { chave: string, quantidade: number }[] {
    const resultado: { chave: string, quantidade: number }[] = new Array(0);

    for (let i = 0; i < this.vendas.length; i++) {
      const v = this.vendas[i];
      let chave: string = "";
      switch (dim) {
        case "filial": chave = v.filial; break;
        case "categoria": chave = v.categoria; break;
        case "autor": chave = v.autor; break;
        case "mes": chave = v.mes.toString(); break;
        case "ano": chave = v.ano.toString(); break;
        default: chave = ""; break;
      }

      let encontrado = false;
      for (let j = 0; j < resultado.length; j++) {
        if (resultado[j].chave === chave) {
          resultado[j].quantidade += v.quantidade;
          encontrado = true;
          break;
        }
      }

      if (!encontrado) {
        // adicionar novo agrupamento
        const novoArray = new Array(resultado.length + 1);
        for (let j = 0; j < resultado.length; j++) {
          novoArray[j] = resultado[j];
        }
        novoArray[resultado.length] = { chave: chave, quantidade: v.quantidade };
        resultado.length = novoArray.length;
        for (let j = 0; j < novoArray.length; j++) {
          resultado[j] = novoArray[j];
        }
      }
    }

    return resultado;
  }

  // Agrupa as vendas por duas dimensões e soma as quantidades
  agruparPorDuasDimensoes(dim1: string, dim2: string): { [chave1: string]: { [chave2: string]: number } } {
    const resultado: { [chave1: string]: { [chave2: string]: number } } = {};
    for (let i = 0; i < this.vendas.length; i++) {
      const v = this.vendas[i];
      const chave1 = (v as any)[dim1].toString();
      const chave2 = (v as any)[dim2].toString();
      if (!resultado[chave1]) resultado[chave1] = {};
      if (!resultado[chave1][chave2]) resultado[chave1][chave2] = 0;
      resultado[chave1][chave2] += v.quantidade;
    }
    return resultado;
  }
}

// Instância única do cubo
const cubo = new CuboDeAnalise();

// Cadastro de vendas
const formVenda = document.getElementById("formVenda") as HTMLFormElement;
const mensagemVenda = document.getElementById("mensagemVenda") as HTMLElement;

formVenda.onsubmit = (e) => {
  e.preventDefault();
  const ano = parseInt((document.getElementById("ano") as HTMLInputElement).value);
  const mes = parseInt((document.getElementById("mes") as HTMLInputElement).value);
  const filial = (document.getElementById("filial") as HTMLInputElement).value;
  const categoria = (document.getElementById("categoria") as HTMLInputElement).value;
  const autor = (document.getElementById("autor") as HTMLInputElement).value;
  const quantidade = parseInt((document.getElementById("quantidade") as HTMLInputElement).value);

  const venda = new Venda(ano, mes, filial, categoria, autor, quantidade);
  cubo.adicionarVenda(venda);

  mensagemVenda.textContent = "Venda cadastrada com sucesso!";

  formVenda.reset();

  analiseSection.style.display = "block";
  mainHeader.style.display = "block";
  formVendaContainer.style.display = "none";
};

// Gerar gráfico
const btnGerar = document.getElementById("gerarGrafico") as HTMLButtonElement;
btnGerar.onclick = () => {
  const dimensao1 = (document.getElementById("dimensaoX") as HTMLSelectElement).value;
  const dimensao2 = (document.getElementById("dimensaoY") as HTMLSelectElement).value;
  if (dimensao1 === dimensao2) {
    alert("Selecione dimensões diferentes para X e Y.");
    return;
  }
  const dados = cubo.agruparPorDuasDimensoes(dimensao1, dimensao2);
  desenharGraficoDuasDimensoes(dados, dimensao1, dimensao2);
};

// Gráfico de uma dimensão
function desenharGrafico(dados: { chave: string, quantidade: number }[]): void {
  const canvas = document.getElementById("canvas") as HTMLCanvasElement;
  const ctx = canvas.getContext("2d")!;
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  const larguraBarra = 40;
  const espacamento = 20;
  const alturaMax = 300;
  const maxQuantidade = getMaxQuantidade(dados);

  for (let i = 0; i < dados.length; i++) {
    const altura = (dados[i].quantidade / maxQuantidade) * alturaMax;
    const x = i * (larguraBarra + espacamento) + 50;
    const y = canvas.height - altura - 30;

    ctx.fillStyle = "steelblue";
    ctx.fillRect(x, y, larguraBarra, altura);

    // Texto chave
    ctx.fillStyle = "black";
    ctx.fillText(dados[i].chave, x, canvas.height - 10);

    // Quantidade
    ctx.fillText(dados[i].quantidade.toString(), x, y - 5);
  }
}

function getMaxQuantidade(dados: { chave: string, quantidade: number }[]): number {
  let max = 0;
  for (let i = 0; i < dados.length; i++) {
    if (dados[i].quantidade > max) {
      max = dados[i].quantidade;
    }
  }
  return max;
}

// Gráfico de duas dimensões (barras agrupadas)
function desenharGraficoDuasDimensoes(
  dados: { [chave1: string]: { [chave2: string]: number } },
  dim1: string,
  dim2: string
): void {
  const canvas = document.getElementById("canvas") as HTMLCanvasElement;
  const ctx = canvas.getContext("2d")!;
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  const chaves1 = Object.keys(dados);
  const chaves2: string[] = [];
  chaves1.forEach(k1 => {
    Object.keys(dados[k1]).forEach(k2 => {
      if (!chaves2.includes(k2)) chaves2.push(k2);
    });
  });

  const larguraBarra = 20;
  const espacamento = 10;
  const grupoEspaco = 40;
  const alturaMax = 300;

  // Descobrir o máximo
  let maxQuantidade = 0;
  chaves1.forEach(k1 => {
    chaves2.forEach(k2 => {
      if (dados[k1][k2] && dados[k1][k2] > maxQuantidade) {
        maxQuantidade = dados[k1][k2];
      }
    });
  });

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
    // Nome do grupo principal
    ctx.fillText(chaves1[i], i * (chaves2.length * (larguraBarra + espacamento) + grupoEspaco) + 50, canvas.height - 10);
  }
}
