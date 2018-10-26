asynctest(
  'browser.tinymce.plugins.imagetools.ImageToolsPluginTest',
  [
    'ephox.agar.api.GeneralSteps',
    'ephox.agar.api.Pipeline',
    'ephox.agar.api.RawAssertions',
    'ephox.agar.api.Step',
    'ephox.mcagar.api.TinyApis',
    'ephox.mcagar.api.TinyLoader',
    'tinymce.plugins.imagetools.Plugin',
    'tinymce.themes.modern.Theme',
    'tinymce.core.util.URI',
    'tinymce.plugins.imagetools.test.ImageUtils'
  ],
  function (GeneralSteps, Pipeline, RawAssertions, Step, TinyApis, TinyLoader, Plugin, ModernTheme, URI, ImageUtils) {
    var success = arguments[arguments.length - 2];
    var failure = arguments[arguments.length - 1];
    var uploadHandlerState = ImageUtils.createStateContainer();
    var sourceImageUrl = '/project/src/plugins/imagetools/src/demo/img/dogleft.jpg';

    Plugin();
    ModernTheme();

    var sAssertUploadFilename = function (expected) {
      return Step.sync(function () {
        var blobInfo = uploadHandlerState.get().blobInfo;
        RawAssertions.assertEq('Should be expected file name', expected, blobInfo.filename());
      });
    };

    var sAssertUri = function (expected) {
      return Step.sync(function () {
        var blobInfo = uploadHandlerState.get().blobInfo;
        var uri = new URI(blobInfo.uri());
        RawAssertions.assertEq('Should be expected uri', expected, uri.relative);
      });
    };

    var sTestGenerateFileName = function (tinyApis, editor) {
      return GeneralSteps.sequence([
        uploadHandlerState.sResetState,
        tinyApis.sSetSetting('images_reuse_filename', false),
        ImageUtils.sLoadImage(editor, sourceImageUrl),
        tinyApis.sSelect('img', []),
        ImageUtils.sExecCommand(editor, 'mceImageFlipHorizontal'),
        ImageUtils.sWaitForBlobImage(editor),
        ImageUtils.sUploadImages(editor),
        uploadHandlerState.sWaitForState,
        sAssertUploadFilename('imagetools0.jpg')
      ]);
    };

    var sTestReuseFilename = function (tinyApis, editor) {
      return GeneralSteps.sequence([
        uploadHandlerState.sResetState,
        tinyApis.sSetSetting('images_reuse_filename', true),
        ImageUtils.sLoadImage(editor, sourceImageUrl),
        tinyApis.sSelect('img', []),
        ImageUtils.sExecCommand(editor, 'mceImageFlipHorizontal'),
        ImageUtils.sWaitForBlobImage(editor),
        ImageUtils.sUploadImages(editor),
        uploadHandlerState.sWaitForState,
        sAssertUploadFilename('dogleft.jpg'),
        sAssertUri(sourceImageUrl)
      ]);
    };

    TinyLoader.setup(function (editor, onSuccess, onFailure) {
      var tinyApis = TinyApis(editor);

      Pipeline.async({}, [
        sTestGenerateFileName(tinyApis, editor),
        sTestReuseFilename(tinyApis, editor)
      ], onSuccess, onFailure);
    }, {
      plugins: 'imagetools',
      automatic_uploads: false,
      images_upload_handler: uploadHandlerState.handler(sourceImageUrl),
      skin_url: '/project/src/skins/lightgray/dist/lightgray'
    }, success, failure);
  }
);
