# VideoEditorApp

An example WPF video editor with support for basic 1v1 edits using **FFmpeg**.

## Features

- Open a primary video and optional overlay video
- Preview playback with play/pause/stop controls
- Add multiple text overlays on top of the preview
- Apply simple effects such as grayscale or fade-in
- Combine two videos side by side (1v1 split screen) when exporting
- Export the edited result using FFmpeg commands

## Requirements

- Windows with .NET SDK 7.0 or later
- [`ffmpeg`](https://ffmpeg.org/) available on the system `PATH`

## Build and Run

```bash
cd VideoEditorApp
 dotnet run
```

The application invokes `ffmpeg` to perform the heavy lifting for edits and effects.
Ensure `ffmpeg` is installed and accessible from the command line.
