---
title: Hello world
metaTitle: Hello world - Guide - Handsontable Documentation
permalink: /9.0/hello-world
canonicalUrl: /hello-world
tags:
  - demo
---

# Hello world

Before you dive deep into Handsontable, check out our demo app.

Rather than display "Hello, World!", the demo contains 100 rows and 13 columns packed with features:

- [Column groups](@/guides/columns/column-groups.md)
- [Column menu](@/guides/columns/column-menu.md)
- [Filters](@/guides/columns/column-filter.md)
- [Sorting](@/guides/rows/row-sorting.md)
- And way more than that!

Want to play with the code yourself? Select "Open Sandbox" in the frame's bottom right corner.

<HelloWorld :demos="[
  {
    name: 'JavaScript',
    title: 'Handsontable JavaScript Data Grid - Hello World App',
    codeSandboxId: 'handsontable-javascript-data-grid-hello-world-app-dzx8f',
    selectedFile: '/src/index.js',
  },
  {
    name: 'TypeScript',
    title: 'Handsontable TypeScript Data Grid - Hello World App',
    codeSandboxId: 'handsontable-typescript-data-grid-hello-world-app-145es',
    selectedFile: '/src/index.ts',
  },
  {
    name: 'React',
    title: 'Handsontable React Data Grid - Hello World App',
    codeSandboxId: 'handsontable-react-data-grid-hello-world-app-yt46w',
    selectedFile: '/src/index.tsx',
  },
  {
    name: 'Angular',
    title: 'Handsontable Angular Data Grid - Hello World App',
    codeSandboxId: 'handsontable-angular-data-grid-hello-world-app-50pb7',
    selectedFile: '/src/data-grid/data-grid.component.ts',
  },
  {
    name: 'Vue',
    title: 'Handsontable Vue Data Grid - Hello World App',
    codeSandboxId: 'handsontable-vue-data-grid-hello-world-app-hh2hk',
    selectedFile: '/src/components/DataGrid.vue',
  },
]"></HelloWorld>
