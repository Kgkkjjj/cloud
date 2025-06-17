using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;
using VideoEditorApp.Models;

namespace VideoEditorApp.Services
{
    public class FFmpegEditor
    {
        private readonly string _ffmpegPath;

        public FFmpegEditor(string ffmpegPath = "ffmpeg")
        {
            _ffmpegPath = ffmpegPath;
        }

        public void AddTextOverlay(VideoProject project, string text)
        {
            if (!string.IsNullOrWhiteSpace(text))
                project.TextOverlays.Add(text);
        }

        public void AddEffect(VideoProject project, EffectOption effect)
        {
            if (effect != EffectOption.None)
                project.Effects.Add(effect);
        }

        public void ExportProject(VideoProject project, string outputPath)
        {
            if (string.IsNullOrEmpty(project.MainVideoPath))
                throw new InvalidOperationException("Main video not specified.");

            var args = new StringBuilder();
            args.Append($"-i \"{project.MainVideoPath}\" ");
            if (!string.IsNullOrEmpty(project.OverlayVideoPath))
            {
                args.Append($"-i \"{project.OverlayVideoPath}\" ");
            }

            var filters = new List<string>();
            if (!string.IsNullOrEmpty(project.OverlayVideoPath))
            {
                filters.Add("[0:v][1:v]hstack=inputs=2[vtmp]");
            }
            foreach (var t in project.TextOverlays)
            {
                filters.Add($"drawtext=text='{t}':x=10:y=10");
            }
            if (project.Effects.Contains(EffectOption.Grayscale))
                filters.Add("format=gray");
            if (project.Effects.Contains(EffectOption.FadeIn))
                filters.Add("fade=t=in:st=0:d=3");

            if (filters.Count > 0)
            {
                string filterString = string.Join(",", filters);
                if (!string.IsNullOrEmpty(project.OverlayVideoPath))
                    args.Append($"-filter_complex \"{filterString}\" -map \"[vtmp]\" ");
                else
                    args.Append($"-vf \"{filterString}\" ");
            }

            args.Append($"\"{outputPath}\"");

            var psi = new ProcessStartInfo
            {
                FileName = _ffmpegPath,
                Arguments = args.ToString(),
                UseShellExecute = false
            };
            Process.Start(psi);
        }
    }
}
