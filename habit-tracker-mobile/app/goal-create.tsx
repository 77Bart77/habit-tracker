import { Picker } from "@react-native-picker/picker";
import { useRouter } from "expo-router";
import { useEffect, useMemo, useState } from "react";
import { ActivityIndicator, Switch, View } from "react-native";

import { AppBackground } from "../src/ui/AppBackground";
import { AppButton } from "../src/ui/AppButton";
import { AppCard } from "../src/ui/AppCard";
import { AppHeader } from "../src/ui/AppHeader";
import { AppInput } from "../src/ui/AppInput";
import { AppText } from "../src/ui/AppText";

import { getGoalCategories, type GoalCategory } from "../src/api/categories";
import { createGoal } from "../src/api/goals";

function toYmd(d: Date) {
  return d.toISOString().slice(0, 10);
}
function addDays(base: Date, days: number) {
  const d = new Date(base);
  d.setDate(d.getDate() + days);
  return d;
}

export default function GoalCreateScreen() {
  const router = useRouter();

  const [title, setTitle] = useState("");
  const [desc, setDesc] = useState("");
  const [status, setStatus] = useState("");

  const [isPublic, setIsPublic] = useState(false);

  // minimalne pola do create
  const [intervalDays, setIntervalDays] = useState("1");
  const [startDate, setStartDate] = useState(toYmd(new Date()));
  const [endDate, setEndDate] = useState(toYmd(addDays(new Date(), 30)));

  // kategorie
  const [catLoading, setCatLoading] = useState(true);
  const [categories, setCategories] = useState<GoalCategory[]>([]);
  const [categoryId, setCategoryId] = useState<number | null>(null);

  const canSave = useMemo(() => title.trim().length >= 3, [title]);

  useEffect(() => {
    (async () => {
      setCatLoading(true);
      try {
        const cats = await getGoalCategories();
        setCategories(cats);

        // ustaw domyślną kategorię (pierwszą z listy)
        const firstId =
          cats?.[0]?.id && Number.isFinite(Number(cats[0].id))
            ? Number(cats[0].id)
            : null;

        setCategoryId(firstId);
      } catch (e: any) {
        setStatus("Błąd ładowania kategorii: " + String(e?.message ?? e));
      } finally {
        setCatLoading(false);
      }
    })();
  }, []);

  async function handleSave() {
    if (!canSave) {
      setStatus("Tytuł musi mieć min. 3 znaki.");
      return;
    }
    if (!categoryId) {
      setStatus("Wybierz kategorię.");
      return;
    }

    const interval = Number(intervalDays);
    if (!Number.isFinite(interval) || interval <= 0) {
      setStatus("Interval days musi być liczbą > 0 (np. 1).");
      return;
    }

    if (!startDate || startDate.length !== 10) {
      setStatus("Start date musi być w formacie YYYY-MM-DD.");
      return;
    }
    if (!endDate || endDate.length !== 10) {
      setStatus("End date musi być w formacie YYYY-MM-DD.");
      return;
    }

    setStatus("Zapisuję...");

    try {
      await createGoal({
        goal_category_id: categoryId,
        title: title.trim(),
        interval_days: interval,
        start_date: startDate,
        end_date: endDate,
        description: desc.trim() ? desc.trim() : undefined,
        is_public: isPublic,
      });

      setStatus("Zapisano ✅");
      router.replace("/goals"); // wróć do listy i odśwież
    } catch (e: any) {
      setStatus("Błąd: " + String(e?.message ?? e));
    }
  }

  return (
    <AppBackground>
      <AppHeader
        title="Dodaj cel"
        subtitle="Utwórz nowy cel"
        rightActions={[{ title: "Wróć", onPress: () => router.back() }]}
      />

      <AppCard>
        {/* Kategoria */}
        <AppText variant="small" style={{ marginBottom: 8 }}>
          Kategoria
        </AppText>

        {catLoading ? (
          <View style={{ paddingVertical: 10 }}>
            <ActivityIndicator />
          </View>
        ) : (
          <View
            style={{
              borderWidth: 1,
              borderRadius: 14,
              overflow: "hidden",
            }}
          >
            <Picker
              selectedValue={categoryId ?? undefined}
              onValueChange={(v) => setCategoryId(Number(v))}
            >
              {categories.map((c) => (
                <Picker.Item
                  key={c.id}
                  label={String(c.title ?? c.name ?? `Kategoria #${c.id}`)}
                  value={c.id}
                />
              ))}
            </Picker>
          </View>
        )}

        <View style={{ height: 12 }} />

        {/* Publiczny */}
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

        <View style={{ height: 12 }} />

        {/* Tytuł */}
        <AppText variant="small" style={{ marginBottom: 8 }}>
          Tytuł
        </AppText>
        <AppInput value={title} onChangeText={setTitle} placeholder="Np. Siłownia" />

        <View style={{ height: 12 }} />

        {/* Opis */}
        <AppText variant="small" style={{ marginBottom: 8 }}>
          Opis
        </AppText>
        <AppInput
          value={desc}
          onChangeText={setDesc}
          placeholder="Krótki opis…"
          multiline
          style={{ minHeight: 90 }}
        />

        <View style={{ height: 12 }} />

        {/* Interval */}
        <AppText variant="small" style={{ marginBottom: 8 }}>
          Co ile dni (interval_days)
        </AppText>
        <AppInput
          value={intervalDays}
          onChangeText={setIntervalDays}
          placeholder="Np. 1"
          keyboardType="numeric"
        />

        <View style={{ height: 12 }} />

        {/* Daty */}
        <AppText variant="small" style={{ marginBottom: 8 }}>
          Start date (YYYY-MM-DD)
        </AppText>
        <AppInput value={startDate} onChangeText={setStartDate} placeholder="YYYY-MM-DD" />

        <View style={{ height: 12 }} />

        <AppText variant="small" style={{ marginBottom: 8 }}>
          End date (YYYY-MM-DD)
        </AppText>
        <AppInput value={endDate} onChangeText={setEndDate} placeholder="YYYY-MM-DD" />

        <View style={{ height: 14 }} />

        <AppButton title="Zapisz" onPress={handleSave} disabled={!canSave || catLoading} />

        {!!status && (
          <AppText variant="small" style={{ marginTop: 10, opacity: 0.9 }}>
            {status}
          </AppText>
        )}
      </AppCard>
    </AppBackground>
  );
}