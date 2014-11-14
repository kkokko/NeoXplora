unit ProtoOrSentence;

interface

uses
  EntityWithName, EntityFieldNamesToken;

type
  TProtoOrSentence = class(TEntityWithName)
  public
    type
      TSentenceType = (stProto, stSentence);
  private
    FSentenceType: TSentenceType;
  public
    class var
      Tok_SentenceType: TEntityFieldNamesToken;
  published
    property Id;
    property Name;
    property SentenceType: TSentenceType read FSentenceType write FSentenceType;
  end;

implementation

initialization
  TProtoOrSentence.RegisterEntityClass;
  TProtoOrSentence.RegisterToken(TProtoOrSentence.Tok_SentenceType, 'SentenceType');

end.
