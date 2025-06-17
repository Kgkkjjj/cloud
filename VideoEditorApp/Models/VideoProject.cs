using System.Collections.Generic;

namespace VideoEditorApp.Models
{
    public class VideoProject
    {
        public string? MainVideoPath { get; set; }
        public string? OverlayVideoPath { get; set; }
        public List<string> TextOverlays { get; } = new List<string>();
        public List<EffectOption> Effects { get; } = new List<EffectOption>();
    }

    public enum EffectOption
    {
        None,
        Grayscale,
        FadeIn
    }
}
