import { useLocalSearchParams, useRouter } from "expo-router";
import { useCallback, useEffect, useMemo, useState } from "react";
import { ActivityIndicator, Switch, View } from "react-native";
import { getGoal, updateGoal } from "../src/api/goals";

import { AppBackground } from "../src/ui/AppBackground";
import { AppButton } from "../src/ui/AppButton";
import { AppCard } from "../src/ui/AppCard";
import { AppHeader } from "../src/ui/AppHeader";
import { AppInput } from "../src/ui/AppInput";
import { AppText } from "../src/ui/AppText";

export default function GoalEditScreen() {
  const router = useRouter();
  const params = useLocalSearchParams();
  const id = useMemo(() => Number(params.id), [params.id]);

  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState("");

  const [title, setTitle] = useState("");
  const [description, setDescription] = useState("");
  const [isPublic, setIsPublic] = useState(false);

  const load = useCallback(async () => {
    if (!Number.isFinite(id) || id <= 0) {
      setError("Nieprawidłowe ID celu.");
      setLoading(false);
      return;
    }

    setError("");
    setLoading(true);

    try {
      const g = await getGoal(id);
      setTitle(String(g?.title ?? ""));
      setDescription(String(g?.description ?? ""));
      setIsPublic(!!g?.is_public);
    } catch (e: any) {
      setError(String(e?.message ?? e));
    } finally {
      setLoading(false);
    }
  }, [id]);

  useEffect(() => {
    load();
  }, [load]);

  async function onSave() {
    if (!title.trim()) {
      setError("Tytuł jest wymagany.");
      return;
    }

    setError("");
    setSaving(true);

    try {
      await updateGoal(id, {
        title: title.trim(),
        description: description.trim() ? description.trim() : null,
        is_public: isPublic,
      });

      // wracamy do szczegółów (tam masz focusEffect -> zrobi reload)
      router.back();
    } catch (e: any) {
      setError(String(e?.message ?? e));
    } finally {
      setSaving(false);
    }
  }

  return (
    <AppBackground>
      <AppHeader
        title="Edytuj cel"
        subtitle={title ? `ID: ${id}` : `ID: ${id}`}
        rightActions={[
          { title: "Wróć", onPress: () => router.back() },
          { title: "Odśwież", onPress: load },
        ]}
      />

      {loading ? (
        <View style={{ flex: 1, justifyContent: "center", alignItems: "center" }}>
          <ActivityIndicator />
          <AppText variant="small" style={{ marginTop: 10 }}>
            Ładuję dane…
          </AppText>
        </View>
      ) : (
        <AppCard>
          {!!error && (
            <AppText style={{ marginBottom: 12, color: "crimson" }}>
              Błąd: {error}
            </AppText>
          )}

          <AppText variant="small" style={{ marginBottom: 8 }}>
            Tytuł
          </AppText>
          <AppInput
            value={title}
            onChangeText={setTitle}
            placeholder="Np. Siłownia"
          />

          <View style={{ height: 12 }} />

          <AppText variant="small" style={{ marginBottom: 8 }}>
            Opis
          </AppText>
          <AppInput
            value={description}
            onChangeText={setDescription}
            placeholder="Opcjonalnie…"
            multiline
            style={{ minHeight: 90 }}
          />

          <View style={{ height: 12 }} />

          <View
            style={{
              flexDirection: "row",
              alignItems: "center",
              justifyContent: "space-between",
            }}
          >
            <AppText variant="small">Publiczny cel</AppText>
            <Switch value={isPublic} onValueChange={setIsPublic} />
          </View>

          <View style={{ height: 16 }} />

          <View style={{ flexDirection: "row", gap: 10 }}>
            <View style={{ flex: 1 }}>
              <AppButton
                title="Wróć"
                onPress={() => router.back()}
                disabled={saving}
              />
            </View>
            <View style={{ flex: 1 }}>
              <AppButton
                title={saving ? "Zapisuję..." : "Zapisz"}
                onPress={onSave}
                disabled={saving}
              />
            </View>
          </View>

          {saving && (
            <AppText variant="small" style={{ marginTop: 10, opacity: 0.85 }}>
              Trwa zapis…
            </AppText>
          )}
        </AppCard>
      )}
    </AppBackground>
  );
}