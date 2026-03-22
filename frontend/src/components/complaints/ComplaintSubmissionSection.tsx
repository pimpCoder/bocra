import { type FormEvent, useEffect, useMemo, useState } from 'react'
import { complaintCategories, type ComplaintFormValues, type ComplaintRecord } from '../../types/complaints'
import { submitComplaint } from '../../utils/complaintsStorage'
import ActionButton from '../ui/ActionButton'
import InputField from '../ui/InputField'

type ComplaintSubmissionSectionProps = {
  onSuccess: (record: ComplaintRecord) => void
  resetToken?: number
}

type FormErrors = Partial<Record<keyof ComplaintFormValues, string>>

const initialValues: ComplaintFormValues = {
  name: '',
  email: '',
  company: '',
  phone: '',
  category: 'Research',
  complaint: '',
}

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

function ComplaintSubmissionSection({ onSuccess, resetToken = 0 }: ComplaintSubmissionSectionProps) {
  const [values, setValues] = useState<ComplaintFormValues>(initialValues)
  const [errors, setErrors] = useState<FormErrors>({})
  const [isSubmitting, setIsSubmitting] = useState<boolean>(false)

  useEffect(() => {
    setValues(initialValues)
    setErrors({})
  }, [resetToken])

  const categoryOptions = useMemo(
    () => complaintCategories.map((category) => ({ label: category, value: category })),
    [],
  )

  const validate = (draft: ComplaintFormValues): FormErrors => {
    const nextErrors: FormErrors = {}
    if (!draft.name.trim()) nextErrors.name = 'Name is required'
    if (!draft.email.trim()) nextErrors.email = 'Email is required'
    if (draft.email.trim() && !emailPattern.test(draft.email.trim())) {
      nextErrors.email = 'Please enter a valid email address'
    }
    if (!draft.company.trim()) nextErrors.company = 'Company is required'
    if (!draft.phone.trim()) nextErrors.phone = 'Phone is required'
    if (!draft.category.trim()) nextErrors.category = 'Complaint type is required'
    if (!draft.complaint.trim()) nextErrors.complaint = 'Complaint details are required'
    return nextErrors
  }

  const onSubmit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault()
    const nextErrors = validate(values)
    setErrors(nextErrors)
    if (Object.keys(nextErrors).length > 0) return

    setIsSubmitting(true)
    window.setTimeout(() => {
      const record = submitComplaint({
        ...values,
        name: values.name.trim(),
        email: values.email.trim(),
        company: values.company.trim(),
        phone: values.phone.trim(),
        complaint: values.complaint.trim(),
      })
      setIsSubmitting(false)
      onSuccess(record)
    }, 700)
  }

  return (
    <section className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
      <h1 className="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">Complaint Submission</h1>
      <p className="mt-2 max-w-2xl text-sm text-slate-600 md:text-base">
        Submit your complaint using the form below. All fields marked with * are required.
      </p>

      <form className="mt-6 grid gap-4" noValidate onSubmit={onSubmit}>
        <div className="grid gap-4 md:grid-cols-2">
          <InputField
            id="complaint-name"
            label="Name"
            required
            value={values.name}
            onChange={(value) => setValues((prev) => ({ ...prev, name: value }))}
            error={errors.name}
          />
          <InputField
            id="complaint-email"
            label="Email"
            inputType="email"
            required
            value={values.email}
            onChange={(value) => setValues((prev) => ({ ...prev, email: value }))}
            error={errors.email}
          />
          <InputField
            id="complaint-company"
            label="Company"
            required
            value={values.company}
            onChange={(value) => setValues((prev) => ({ ...prev, company: value }))}
            error={errors.company}
          />
          <InputField
            id="complaint-phone"
            label="Phone"
            inputType="tel"
            required
            value={values.phone}
            onChange={(value) => setValues((prev) => ({ ...prev, phone: value }))}
            error={errors.phone}
          />
        </div>

        <InputField
          as="select"
          id="complaint-category"
          label="Type of complaint"
          required
          value={values.category}
          onChange={(value) =>
            setValues((prev) => ({ ...prev, category: value as ComplaintFormValues['category'] }))
          }
          options={categoryOptions}
          error={errors.category}
        />

        <InputField
          as="textarea"
          id="complaint-details"
          label="Complaint"
          required
          value={values.complaint}
          onChange={(value) => setValues((prev) => ({ ...prev, complaint: value }))}
          error={errors.complaint}
          rows={7}
        />

        <div className="pt-1">
          <ActionButton isLoading={isSubmitting} type="submit">
            Submit Complaint
          </ActionButton>
        </div>
      </form>
    </section>
  )
}

export default ComplaintSubmissionSection
