unit QAObject;

{$mode objfpc}{$H+}

interface

uses
  Entity;

type
  { TQAObject }

  TQAObject = class(TEntity)
  private
    FAnswer: string;
    FQARule: string;
    FQuestion: string;
    FStoryId: Integer;
  published
    property Answer: string read FAnswer write FAnswer;
    property Id;
    property QARule: string read FQARule write FQARule;
    property Question: string read FQuestion write FQuestion;
    property StoryId: Integer read FStoryId write FStoryId;
  end;

implementation

{ TQAObject }

initialization
  TQAObject.RegisterEntityClass;

end.
