unit LexiconLine;

interface

uses
  Entity;

type
  TLexiconLine = class(TEntity)
  private
    FWord: string;
    FPos: string;
  published
    property Id;
    property Word: string read FWord write FWord;
    property Pos: string read FPos write FPos;
  end;

implementation

initialization
  TLexiconLine.RegisterEntityClassWithMappingToTable('lexicon');

end.
