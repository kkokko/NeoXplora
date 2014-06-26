program project1;

{$mode objfpc}{$H+}

uses
  {$IFDEF UNIX}{$IFDEF UseCThreads}
  cthreads,
  {$ENDIF}{$ENDIF}
  Interfaces, // this includes the LCL widgetset
  Forms, tachartlazaruspkg, Main, NASTypes, StoryObject, RepCalculator,
  SentenceAlgorithm, PosTagger, SentenceList, SentenceListElement, crep,
  QAAlgorithm, CRepDecoder, Hypernym, MySQLExport, MySQLImport, CoreUnit,
  SentenceSplitter, RequestGetImportInfo, ServerInterface, superobject,
  RequestGetStoriesForImport, RequestSetStoriesFromExport, EditStory, Entity,
  TypesConsts, StringArray, FieldInfo, EntityManager, SkyLists, TypesFunctions,
  EntityList, EntityJsonWriter, EntityWriter, SkyIdList, EntityJSonReader,
  EntityReader, GenericEntity, ProtoObject, ProtoContainer, PanelProto,
  PanelSentence, Upgrade, SkyException, MessageInfoData, ExceptionManagerUnit,
  FWTranslations, EntityFieldNamesToken, EntityTokens, EntityMapping, 
EntityMappingManager;

{$R *.res}

begin
  RequireDerivedFormResource := True;
  Application.Initialize;
  Application.CreateForm(TMainForm, MainForm);
  Application.Run;
end.

