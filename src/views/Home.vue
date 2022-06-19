<script setup>
import Axios from 'axios'
</script>

<template>
  <main>
    <form name="theForm" @submit.prevent="getWords">
      <input type="text" name="word" v-model="word" />
      <input type="submit" name="submitButton" value=" Go " />
    </form>
    <br/>
    <div v-for="g in theWords" class="grp-cont">
      <div v-for="r in g">{{r.toUpperCase()}}</div>
    </div>
  </main>
</template>

<script>
export default {
  data: function() {
    return {
      word: '',
      combinations: [],
      theWords: {},
      indices: [],
      api_call: 0,
      api_response: 0
    }
  },
  methods: {
    getWords: function() {
      //reset combinations
      this.combinations = []
      //this.combinations[0] = []
      //this.combinations[1] = []
      //this.combinations[2] = []
      
      //reset results
      this.theWords = {}
      //this.results[0] = []
      //this.results[1] = []
      //this.results[2] = []
      
      this.indices = []
        
      let str = this.word
      let n = str.length
      let j = []
      
      //returned combinations are not used
      //while constructing possible combinations, they are stored in the this.theWords property
      j = this.jumble(str)
      
      //console.log( this.combinations )
      
      
      let w = 0
      let x = 0
      let batch = []
      
      for (x in this.combinations) {
          batch = []
          
          for (w in this.combinations[x]) {
            batch.push(this.combinations[x][w])
          }
          
          //--------------------------
          // Check the last batch of combinations
          if (batch.length) {
            this.api_call++
            
            Axios.post(this.apiURLs.localhost, { fn: 'validate_words', grp: x, words: batch })
            .then(response => {
              this.api_response++
              
              let idx = parseInt( response.data.grp )
              
              if (typeof this.theWords[idx] == 'undefined') {
                //console.log(idx)
                this.theWords[idx] = []
                this.indices.push(idx)
              }
              
              for(let i in response.data.results) {
                if (this.theWords[idx].indexOf( response.data.results[i].word ) < 0) {
                  this.theWords[idx].push( response.data.results[i].word )
                }
              }
              
              if (this.api_call == this.api_response) {
                this.sortGroup()
                this.$forceUpdate()
              }
            })
            .catch((error) => console.log(error))
            
            //console.log(batch)
          }
          //-------------------------------
          
      }
      
    },
    
    jumble: function(str) {
      let w = []
      let n = str.length
      
      if (n > 2) {
        for(let x=0; x<n; x++) {
          let c = str[x]
          let tmpStr = ''
        
          for(let y=0; y<str.length; y++) {
            if (x != y) {
              tmpStr += str[y]
            }
          }
          
          let w2 = this.jumble(tmpStr)
          
          w2.forEach(a => {
            let word = c + a
            let l = word.length
            
            w.push( word )
            
            if (l >= 3) {
              if (typeof this.combinations[l] == 'undefined') {
                this.combinations[l] = []
              }
              
              //------------------------------
              // Check valid words by batch and reset storage to save memory
              if (this.combinations[l].length == 2000) {
                this.api_call++
                
                Axios.post(this.apiURLs.localhost, { fn: 'validate_words', grp: l, words: this.combinations[l] })
                .then(response => {
                  this.api_response++
                  
                  let idx = parseInt( response.data.grp )
                  
                  if (typeof this.theWords[idx] == 'undefined') {
                    //console.log(idx)
                    this.theWords[idx] = []
                    this.indices.push(idx)
                  }
                  
                  for(let i in response.data.results) {
                    if (this.theWords[idx].indexOf( response.data.results[i].word ) < 0) {
                      this.theWords[idx].push( response.data.results[i].word )
                    }
                  }
                  
                  if (this.api_call == this.api_response) {
                    this.sortGroup()
                    this.$forceUpdate()
                  }
                })
                .catch((error) => console.log(error))
                
                //reset
                this.combinations[l] = [];
              }
              //-----------------------------
              
              if (this.combinations[l].indexOf(word) < 0) {
                this.combinations[l].push(word)
              }
            }
            
          })
        }
      } else {
        w.push(str[0] + str[1])
        w.push(str[1] + str[0])
      }
      
      return w
    },
    
    sortGroup: function() {
      this.indices.sort((a, b) => {return a - b})
      
      let newArray = []
      for(let i in this.indices) {
        newArray.push( this.theWords[ this.indices[i] ] )
      }
      
      this.theWords = newArray
    }
  },
  mounted() {
    Axios.post(this.apiURLs.localhost, { fn: 'log_visitor' })
    .then(response => {
      console.log('logged', response.data.status)
    })
    .catch((error) => console.log(error))
  }
}
</script>

<style>
body {
    font-family: 'Arial', sans-serif;
}

.grp-cont {
    margin-bottom: 15px;
    letter-spacing: 1px;
}
</style>