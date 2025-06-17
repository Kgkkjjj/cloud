using Microsoft.Win32;
using System.Windows;
using System.Windows.Media;
using System.Windows.Controls;
using VideoEditorApp.Models;
using VideoEditorApp.Services;

namespace VideoEditorApp
{
    public partial class MainWindow : Window
    {
        private readonly VideoProject _project = new VideoProject();
        private readonly FFmpegEditor _editor = new FFmpegEditor();

        public MainWindow()
        {
            InitializeComponent();
        }

        private void OpenMainVideo_Click(object sender, RoutedEventArgs e)
        {
            var dlg = new OpenFileDialog
            {
                Filter = "Video Files (*.mp4;*.avi;*.mov)|*.mp4;*.avi;*.mov"
            };
            if (dlg.ShowDialog() == true)
            {
                _project.MainVideoPath = dlg.FileName;
                VideoPreview.Source = new System.Uri(dlg.FileName);
                VideoPreview.Play();
            }
        }

        private void OpenOverlayVideo_Click(object sender, RoutedEventArgs e)
        {
            var dlg = new OpenFileDialog
            {
                Filter = "Video Files (*.mp4;*.avi;*.mov)|*.mp4;*.avi;*.mov"
            };
            if (dlg.ShowDialog() == true)
            {
                _project.OverlayVideoPath = dlg.FileName;
            }
        }

        private void Play_Click(object sender, RoutedEventArgs e) => VideoPreview.Play();
        private void Pause_Click(object sender, RoutedEventArgs e) => VideoPreview.Pause();
        private void Stop_Click(object sender, RoutedEventArgs e) => VideoPreview.Stop();

        private void AddTextButton_Click(object sender, RoutedEventArgs e)
        {
            _editor.AddTextOverlay(_project, OverlayText.Text);

            var overlay = new TextBlock
            {
                Text = OverlayText.Text,
                Foreground = Brushes.White,
                Background = Brushes.Black,
                Opacity = 0.7
            };
            Canvas.SetTop(overlay, 10);
            Canvas.SetLeft(overlay, 10);
            OverlayCanvas.Children.Add(overlay);
        }

        private void ApplyEffectButton_Click(object sender, RoutedEventArgs e)
        {
            if (EffectCombo.SelectedItem is ComboBoxItem item)
            {
                string effectName = item.Content.ToString() ?? "";
                EffectOption effect = effectName switch
                {
                    "Grayscale" => EffectOption.Grayscale,
                    "FadeIn" => EffectOption.FadeIn,
                    _ => EffectOption.None
                };
                _editor.AddEffect(_project, effect);
            }
        }

        private void Export_Click(object sender, RoutedEventArgs e)
        {
            var dlg = new SaveFileDialog { Filter = "MP4 Video (*.mp4)|*.mp4" };
            if (dlg.ShowDialog() == true)
            {
                _editor.ExportProject(_project, dlg.FileName);
            }
        }
    }
}
