var TRepRecordWord_Implementation = {
  
  construct: function(Parent, Word, Type) {
    this.base(this);
    this.setWord(Word);
    this.setType(Type);
    this.setParentObject(Parent);
  },
  
  properties: {
    Word: null, // string
    Type: null, // (separator, word)
    ParentObject: null, // TRepRecord
    LinkedEntity: null // TRepEntity
  }

};

Sky.Class.Define("NeoX.TRepRecordWord", TRepRecordWord_Implementation);