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
}

// Instância única do cubo
const cubo = new CuboDeAnalise();

// Cadastro de vendas
const formVenda = document.getElementById("formVenda") as HTMLFormElement;
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

  alert("Venda adicionada!");
  formVenda.reset();
};

// Gerar gráfico
const btnGerar = document.getElementById("gerarGrafico") as HTMLButtonElement;
btnGerar.onclick = () => {
  const dimensao = (document.getElementById("dimensaoX") as HTMLSelectElement).value;
  const dados = cubo.agruparPorDimensao(dimensao);

  desenharGrafico(dados);
};

// Desenhar gráfico no canvas
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
