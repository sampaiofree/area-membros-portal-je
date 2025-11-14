
## 🧠 **INSTRUÇÕES PARA FRONT-END EDUX**

Sempre que eu pedir para criar, revisar, corrigir ou melhorar qualquer código do front-end, siga obrigatoriamente o padrão:

### **Tecnologias**

* Laravel Blade
* Livewire **v3**
* Alpine.js
* Tailwind CSS
* HTML simples, limpo e sem frameworks extras

### **Regras principais**

1. **Não usar** Vue, React, jQuery ou Bootstrap.
2. Todo comportamento dinâmico deve ser feito com:

   * Livewire v3 (para lógica e atualização sem recarregar página)
   * Alpine.js (para interações simples no front)
3. Sempre usar classes **Tailwind** para estilizar.
4. Código sempre limpo, curto e fácil de entender.
5. Componentes reutilizáveis sempre que possível.
6. Usar sempre as **cores e fontes do EduX**:

   * Azul: `#1A73E8`
   * Amarelo (CTA): `#FBC02D`
   * Cinza fundo: `#F5F5F5`
   * Preto: `#000000`
   * Títulos: Poppins
   * Textos: Inter
7. Botões devem seguir o padrão:

   * Amarelo
   * Texto preto
   * Bold
   * Bordas arredondadas
   * Altura mínima 50px

### **Quando usar Livewire**

* Listagens
* Filtros
* Busca
* Formulários
* Ações do usuário
* Atualização sem recarregar a página

### **Quando usar Alpine**

* Mostrar/esconder elementos
* Toggles e dropdowns
* Tabs simples
* Estados leves no front

### **Ao gerar qualquer tela**

* Estrutura mobile first
* No máximo 2 colunas no desktop
* Blocos curtos
* Muito espaço em branco
* Nada complexo

### **Formato da resposta do assistente**

Sempre entregar:

* Código Blade
* Componente Livewire (quando necessário)
* Bloco Alpine (se houver interação)
* Tailwind aplicado direto nas classes
* Sem CSS customizado externo (a não ser tokens)

---

# 💬 **Como o assistente deve trabalhar**

Sempre que eu mandar uma tela, página, componente ou arquivo antigo do front-end:

👉 **Reimplemente usando Laravel + Livewire v3 + Alpine + Tailwind.**
👉 **Refatore tudo que estiver fora desse padrão.**
👉 **Simplifique a interface.**
👉 **Use minha identidade visual do EduX.**

