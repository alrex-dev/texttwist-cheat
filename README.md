# Texttwist Game Cheat

A simple Vue.js application that can solve Texttwist or Wordscape game. Made this program just for fun while learning Vue.js.
This can jumble given letters and list down the valid words out of the given letters. It can return 3-letter words up to the n-letter words where n is the maximum number of letters. This communicates to an api to validate the letter combination if it's a valid word or not.

Included here the PHP scripts used for the server side (API) and also the database file that contains the dictionary words.  
api/

## See in action

https://dev.alrexconsus.com/texttwist/

## Limitation
The program becomes slow with 9-letter word. Still looking for a way to handle bigger number of letters ;)


## Version
Vue 2 in Vite


## Recommended IDE Setup

[VSCode](https://code.visualstudio.com/) + [Volar](https://marketplace.visualstudio.com/items?itemName=johnsoncodehk.volar) (and disable Vetur).

## Customize configuration

See [Vite Configuration Reference](https://vitejs.dev/config/).

## Project Setup

```sh
npm install
```

### Compile and Hot-Reload for Development

```sh
npm run dev
```

### Compile and Minify for Production

```sh
npm run build
```
